<?php
/**
 * SpamDetector Class - Naive Bayes Text Classifier for Spam Detection
 * This implements a simple but effective machine learning algorithm
 */

require_once __DIR__ . '/../config/config.php';

class SpamDetector {
    private $pdo;
    private $spamWords = [];
    private $hamWords = [];
    private $spamCount = 0;
    private $hamCount = 0;
    private $vocabulary = [];
    private $trained = false;
    
    // Common spam indicator words with weights
    private $spamIndicators = [
        'free' => 2.5,
        'winner' => 3.0,
        'congratulations' => 2.8,
        'prize' => 2.8,
        'urgent' => 2.5,
        'act now' => 3.0,
        'limited time' => 2.5,
        'click here' => 2.8,
        'buy now' => 2.5,
        'order now' => 2.5,
        'special offer' => 2.5,
        'guaranteed' => 2.3,
        'no obligation' => 2.5,
        'risk free' => 2.5,
        'cash' => 2.0,
        'money' => 1.8,
        'cheap' => 2.0,
        'discount' => 1.8,
        'offer' => 1.5,
        'deal' => 1.5,
        'viagra' => 3.5,
        'lottery' => 3.0,
        'million' => 2.0,
        'billion' => 2.0,
        'bank account' => 2.5,
        'credit card' => 2.0,
        'password' => 2.0,
        'verify' => 1.8,
        'account' => 1.3,
        'suspended' => 2.5,
        'expired' => 2.0,
        'immediately' => 2.0,
        '100%' => 2.5,
        'amazing' => 1.5,
        'incredible' => 1.5,
        'unbelievable' => 2.0
    ];
    
    public function __construct() {
        $this->pdo = getConnection();
        $this->train();
    }
    
    /**
     * Train the classifier using data from database
     */
    public function train() {
        // Get training data from database
        $stmt = $this->pdo->query("SELECT content, is_spam FROM training_data");
        $trainingData = $stmt->fetchAll();
        
        if (empty($trainingData)) {
            // Use fallback training data if database is empty
            $this->useFallbackTraining();
            return;
        }
        
        foreach ($trainingData as $row) {
            $tokens = $this->tokenize($row['content']);
            
            if ($row['is_spam'] == 1) {
                $this->spamCount++;
                foreach ($tokens as $token) {
                    $this->spamWords[$token] = ($this->spamWords[$token] ?? 0) + 1;
                    $this->vocabulary[$token] = true;
                }
            } else {
                $this->hamCount++;
                foreach ($tokens as $token) {
                    $this->hamWords[$token] = ($this->hamWords[$token] ?? 0) + 1;
                    $this->vocabulary[$token] = true;
                }
            }
        }
        
        $this->trained = true;
    }
    
    /**
     * Fallback training data if database is empty
     */
    private function useFallbackTraining() {
        $fallbackData = [
            ['free iphone winner click prize', 1],
            ['urgent verify account password', 1],
            ['make money fast guaranteed', 1],
            ['congratulations selected special offer', 1],
            ['meeting tomorrow project discuss', 0],
            ['thank you order package', 0],
            ['appointment scheduled reminder', 0],
            ['report review attached document', 0],
        ];
        
        foreach ($fallbackData as $data) {
            $tokens = $this->tokenize($data[0]);
            if ($data[1] == 1) {
                $this->spamCount++;
                foreach ($tokens as $token) {
                    $this->spamWords[$token] = ($this->spamWords[$token] ?? 0) + 1;
                    $this->vocabulary[$token] = true;
                }
            } else {
                $this->hamCount++;
                foreach ($tokens as $token) {
                    $this->hamWords[$token] = ($this->hamWords[$token] ?? 0) + 1;
                    $this->vocabulary[$token] = true;
                }
            }
        }
        
        $this->trained = true;
    }
    
    /**
     * Tokenize text into words
     */
    private function tokenize($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        $tokens = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Remove common stop words
        $stopWords = ['the', 'a', 'an', 'is', 'are', 'was', 'were', 'be', 'been', 
                      'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will',
                      'would', 'could', 'should', 'may', 'might', 'must', 'shall',
                      'to', 'of', 'in', 'for', 'on', 'with', 'at', 'by', 'from',
                      'as', 'into', 'through', 'during', 'before', 'after', 'and',
                      'but', 'or', 'nor', 'so', 'yet', 'both', 'either', 'neither',
                      'not', 'only', 'own', 'same', 'than', 'too', 'very', 'just',
                      'i', 'me', 'my', 'myself', 'we', 'our', 'you', 'your', 'he',
                      'him', 'his', 'she', 'her', 'it', 'its', 'they', 'them', 'their'];
        
        return array_filter($tokens, function($token) use ($stopWords) {
            return !in_array($token, $stopWords) && strlen($token) > 2;
        });
    }
    
    /**
     * Calculate probability using Naive Bayes with Laplace smoothing
     */
    private function calculateProbability($tokens, $wordCounts, $classCount, $totalClasses) {
        $vocabSize = count($this->vocabulary);
        $totalWords = array_sum($wordCounts);
        
        // Prior probability
        $logProb = log($classCount / $totalClasses);
        
        // Likelihood for each word with Laplace smoothing
        foreach ($tokens as $token) {
            $wordCount = $wordCounts[$token] ?? 0;
            $logProb += log(($wordCount + 1) / ($totalWords + $vocabSize));
        }
        
        return $logProb;
    }
    
    /**
     * Analyze a message and return spam probability
     */
    public function analyze($message) {
        $tokens = $this->tokenize($message);
        $totalMessages = $this->spamCount + $this->hamCount;
        
        if ($totalMessages == 0) {
            return $this->fallbackAnalysis($message);
        }
        
        // Calculate log probabilities
        $spamLogProb = $this->calculateProbability($tokens, $this->spamWords, $this->spamCount, $totalMessages);
        $hamLogProb = $this->calculateProbability($tokens, $this->hamWords, $this->hamCount, $totalMessages);
        
        // Convert to probability using softmax
        $maxLogProb = max($spamLogProb, $hamLogProb);
        $spamExp = exp($spamLogProb - $maxLogProb);
        $hamExp = exp($hamLogProb - $maxLogProb);
        
        $spamProbability = $spamExp / ($spamExp + $hamExp);
        
        // Apply spam indicator boost
        $indicatorBoost = $this->calculateIndicatorBoost($message);
        $spamProbability = min(0.99, $spamProbability + $indicatorBoost);
        
        // Find detected indicators
        $detectedIndicators = $this->findIndicators($message);
        
        // Determine confidence level
        $confidence = $this->getConfidenceLevel($spamProbability);
        
        return [
            'is_spam' => $spamProbability > 0.5,
            'spam_probability' => round($spamProbability, 4),
            'ham_probability' => round(1 - $spamProbability, 4),
            'confidence' => $confidence,
            'indicators' => $detectedIndicators,
            'word_count' => count($tokens),
            'analysis_method' => 'Naive Bayes Classifier'
        ];
    }
    
    /**
     * Fallback analysis using keyword matching
     */
    private function fallbackAnalysis($message) {
        $boost = $this->calculateIndicatorBoost($message);
        $indicators = $this->findIndicators($message);
        $probability = min(0.95, 0.3 + $boost);
        
        return [
            'is_spam' => $probability > 0.5,
            'spam_probability' => round($probability, 4),
            'ham_probability' => round(1 - $probability, 4),
            'confidence' => $this->getConfidenceLevel($probability),
            'indicators' => $indicators,
            'word_count' => str_word_count($message),
            'analysis_method' => 'Keyword Analysis (Fallback)'
        ];
    }
    
    /**
     * Calculate boost based on spam indicators
     */
    private function calculateIndicatorBoost($message) {
        $message = strtolower($message);
        $boost = 0;
        
        foreach ($this->spamIndicators as $indicator => $weight) {
            if (strpos($message, $indicator) !== false) {
                $boost += $weight * 0.05;
            }
        }
        
        // Check for excessive caps
        $capsRatio = preg_match_all('/[A-Z]/', $message) / max(strlen($message), 1);
        if ($capsRatio > 0.3) {
            $boost += 0.1;
        }
        
        // Check for excessive exclamation marks
        $exclamationCount = substr_count($message, '!');
        if ($exclamationCount > 2) {
            $boost += min(0.15, $exclamationCount * 0.03);
        }
        
        return min(0.4, $boost);
    }
    
    /**
     * Find spam indicators in message
     */
    private function findIndicators($message) {
        $message = strtolower($message);
        $found = [];
        
        foreach ($this->spamIndicators as $indicator => $weight) {
            if (strpos($message, $indicator) !== false) {
                $found[] = [
                    'term' => $indicator,
                    'weight' => $weight,
                    'category' => $this->categorizeIndicator($indicator)
                ];
            }
        }
        
        // Sort by weight descending
        usort($found, function($a, $b) {
            return $b['weight'] <=> $a['weight'];
        });
        
        return array_slice($found, 0, 5);
    }
    
    /**
     * Categorize indicator type
     */
    private function categorizeIndicator($indicator) {
        $categories = [
            'urgency' => ['urgent', 'act now', 'limited time', 'immediately', 'expired'],
            'financial' => ['free', 'money', 'cash', 'cheap', 'discount', 'million', 'billion', 'credit card', 'bank account'],
            'prize' => ['winner', 'congratulations', 'prize', 'lottery', 'selected'],
            'action' => ['click here', 'buy now', 'order now'],
            'suspicious' => ['viagra', 'password', 'verify', 'suspended']
        ];
        
        foreach ($categories as $category => $terms) {
            if (in_array($indicator, $terms)) {
                return $category;
            }
        }
        
        return 'general';
    }
    
    /**
     * Determine confidence level
     */
    private function getConfidenceLevel($probability) {
        $distance = abs($probability - 0.5);
        
        if ($distance > 0.4) return 'very high';
        if ($distance > 0.3) return 'high';
        if ($distance > 0.2) return 'medium';
        if ($distance > 0.1) return 'low';
        return 'very low';
    }
}
