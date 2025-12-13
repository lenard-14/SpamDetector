<?php
/**
 * Message Class - Handles message storage and retrieval
 */

require_once __DIR__ . '/../config/config.php';

class Message {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnection();
    }
    
    /**
     * Save analyzed message to database
     */
    public function save($userId, $content, $analysisResult) {
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (user_id, content, is_spam, spam_probability, confidence, indicators)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $indicators = json_encode($analysisResult['indicators']);
        
        try {
            $stmt->execute([
                $userId,
                $content,
                $analysisResult['is_spam'] ? 1 : 0,
                $analysisResult['spam_probability'],
                $analysisResult['confidence'],
                $indicators
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get messages for a user
     */
    public function getByUserId($userId, $limit = 20, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM messages 
            WHERE user_id = ? 
            ORDER BY analyzed_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$userId, $limit, $offset]);
        
        $messages = $stmt->fetchAll();
        
        // Decode JSON indicators
        foreach ($messages as &$message) {
            $message['indicators'] = json_decode($message['indicators'], true) ?? [];
        }
        
        return $messages;
    }
    
    /**
     * Get statistics for a user
     */
    public function getStats($userId) {
        // Total messages
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM messages WHERE user_id = ?");
        $stmt->execute([$userId]);
        $total = $stmt->fetch()['total'];
        
        // Spam count
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as spam FROM messages WHERE user_id = ? AND is_spam = 1");
        $stmt->execute([$userId]);
        $spam = $stmt->fetch()['spam'];
        
        // This week's stats
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as weekly_total, SUM(is_spam) as weekly_spam 
            FROM messages 
            WHERE user_id = ? AND analyzed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
        $stmt->execute([$userId]);
        $weekly = $stmt->fetch();
        
        $ham = $total - $spam;
        $weeklySpamRate = $weekly['weekly_total'] > 0 
            ? round(($weekly['weekly_spam'] / $weekly['weekly_total']) * 100, 1)
            : 0;
        
        return [
            'total' => $total,
            'spam' => $spam,
            'ham' => $ham,
            'weekly_total' => $weekly['weekly_total'] ?? 0,
            'weekly_spam' => $weekly['weekly_spam'] ?? 0,
            'weekly_spam_rate' => $weeklySpamRate
        ];
    }
    
    /**
     * Delete a message
     */
    public function delete($messageId, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM messages WHERE id = ? AND user_id = ?");
        return $stmt->execute([$messageId, $userId]);
    }
    
    /**
     * Get single message by ID
     */
    public function getById($messageId, $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE id = ? AND user_id = ?");
        $stmt->execute([$messageId, $userId]);
        $message = $stmt->fetch();
        
        if ($message) {
            $message['indicators'] = json_decode($message['indicators'], true) ?? [];
        }
        
        return $message;
    }
}
