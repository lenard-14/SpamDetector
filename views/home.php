<?php
/**
 * Landing Page View
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpamShield - AI-Powered Spam Detection</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <a href="index.php" class="logo">
                <div class="logo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <span>SpamShield</span>
            </a>
            <nav class="nav">
                <?php if (isLoggedIn()): ?>
                    <a href="index.php?page=dashboard" class="nav-link">Dashboard</a>
                    <a href="index.php?page=history" class="nav-link">History</a>
                    <a href="index.php?page=logout" class="btn btn-secondary btn-sm">Logout</a>
                <?php else: ?>
                    <a href="index.php?page=login" class="nav-link">Login</a>
                    <a href="index.php?page=register" class="btn btn-primary btn-sm">Get Started</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1 class="hero-title">Detect Spam with<br>Machine Learning</h1>
                <p class="hero-subtitle">
                    Protect your inbox with our AI-powered spam detection tool. 
                    Using Naive Bayes classification to accurately identify unwanted messages.
                </p>
                <div class="hero-actions">
                    <?php if (isLoggedIn()): ?>
                        <a href="index.php?page=dashboard" class="btn btn-primary">Go to Dashboard</a>
                    <?php else: ?>
                        <a href="index.php?page=register" class="btn btn-primary">Start Free</a>
                        <a href="index.php?page=login" class="btn btn-secondary">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="workflow">
            <div class="container">
                <h2 class="section-title">How It Works</h2>
                <div class="workflow-steps">
                    <div class="workflow-step">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Input</h3>
                        <p class="step-desc">Paste your email or message into the analyzer</p>
                    </div>
                    <div class="workflow-arrow">→</div>
                    <div class="workflow-step">
                        <div class="step-number">2</div>
                        <h3 class="step-title">Process</h3>
                        <p class="step-desc">ML algorithm analyzes text patterns</p>
                    </div>
                    <div class="workflow-arrow">→</div>
                    <div class="workflow-step">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Output</h3>
                        <p class="step-desc">Get instant spam probability results</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Naive Bayes Classifier</h3>
                        <p class="feature-desc">
                            Industry-standard ML algorithm trained on thousands of spam patterns for accurate detection.
                        </p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Real-Time Analysis</h3>
                        <p class="feature-desc">
                            Get instant results with detailed confidence scores and spam indicator breakdown.
                        </p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">History Tracking</h3>
                        <p class="feature-desc">
                            Keep track of all analyzed messages with full history and statistics dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> SpamShield. Built with PHP and Machine Learning.</p>
        </div>
    </footer>
</body>
</html>
