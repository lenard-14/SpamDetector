<?php
/**
 * Message History Page View
 */
$messageModel = new Message();
$messages = $messageModel->getByUserId(getCurrentUserId(), 50);
$stats = $messageModel->getStats(getCurrentUserId());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - SpamShield</title>
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
                <a href="index.php?page=dashboard" class="nav-link">Dashboard</a>
                <a href="index.php?page=history" class="nav-link">History</a>
                <div class="user-info">
                    <span class="user-name">Hello, <?= sanitize(getCurrentUsername()) ?></span>
                    <a href="index.php?page=logout" class="btn btn-secondary btn-sm">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <h1 class="page-title">Analysis History</h1>
            <p class="page-subtitle">View all your previously analyzed messages</p>

            <!-- Stats Summary -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Analyzed</div>
                    <div class="stat-value"><?= $stats['total'] ?></div>
                </div>
                <div class="stat-card spam">
                    <div class="stat-label">Spam Detected</div>
                    <div class="stat-value"><?= $stats['spam'] ?></div>
                </div>
                <div class="stat-card ham">
                    <div class="stat-label">Legitimate</div>
                    <div class="stat-value"><?= $stats['ham'] ?></div>
                </div>
            </div>

            <!-- Messages List -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">All Messages</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($messages)): ?>
                        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 1rem; opacity: 0.5;">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            <p>No messages analyzed yet</p>
                            <a href="index.php?page=dashboard" class="btn btn-primary" style="margin-top: 1rem;">Analyze Your First Message</a>
                        </div>
                    <?php else: ?>
                        <div class="message-list">
                            <?php foreach ($messages as $msg): ?>
                            <div class="message-item" style="flex-direction: column; align-items: stretch;">
                                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                                    <div class="message-icon <?= $msg['is_spam'] ? 'spam' : 'ham' ?>">
                                        <?php if ($msg['is_spam']): ?>
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="15" y1="9" x2="9" y2="15"/>
                                                <line x1="9" y1="9" x2="15" y2="15"/>
                                            </svg>
                                        <?php else: ?>
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="message-content">
                                        <div class="message-text" style="white-space: normal;">
                                            <?= sanitize($msg['content']) ?>
                                        </div>
                                        <div class="message-meta" style="margin-top: 0.75rem;">
                                            <span class="badge <?= $msg['is_spam'] ? 'badge-spam' : 'badge-ham' ?>">
                                                <?= $msg['is_spam'] ? 'Spam' : 'Legitimate' ?>
                                            </span>
                                            <span><?= round($msg['spam_probability'] * 100, 1) ?>% probability</span>
                                            <span>Confidence: <?= ucfirst($msg['confidence']) ?></span>
                                            <span><?= date('M j, Y g:i A', strtotime($msg['analyzed_at'])) ?></span>
                                        </div>
                                        <?php if (!empty($msg['indicators'])): ?>
                                        <div class="indicators-list" style="margin-top: 0.75rem;">
                                            <?php foreach ($msg['indicators'] as $ind): ?>
                                                <span class="indicator-tag"><?= sanitize($ind['term']) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> SpamShield. Built with PHP and Machine Learning.</p>
        </div>
    </footer>
</body>
</html>
