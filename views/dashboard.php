<?php
/**
 * Dashboard Page View
 */
$messageModel = new Message();
$stats = $messageModel->getStats(getCurrentUserId());
$recentMessages = $messageModel->getByUserId(getCurrentUserId(), 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SpamShield</title>
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
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Analyze messages and view your spam detection statistics</p>

            <!-- Stats Cards -->
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
                <div class="stat-card">
                    <div class="stat-label">Weekly Spam Rate</div>
                    <div class="stat-value"><?= $stats['weekly_spam_rate'] ?>%</div>
                </div>
            </div>

            <!-- Analyzer Section -->
            <div class="two-col">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Analyze Message</h2>
                    </div>
                    <div class="card-body">
                        <form id="analyzeForm">
                            <div class="form-group">
                                <label class="form-label" for="message">Paste your message or email content</label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    class="form-input form-textarea" 
                                    placeholder="Enter the message you want to analyze for spam..."
                                    required
                                ></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" id="analyzeBtn">
                                <span id="btnText">Analyze Message</span>
                                <span id="btnSpinner" class="spinner" style="display: none;"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Analysis Result</h2>
                    </div>
                    <div class="card-body">
                        <div id="resultPlaceholder" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 1rem; opacity: 0.5;">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <p>Enter a message and click analyze to see results</p>
                        </div>
                        <div id="resultContainer" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Messages -->
            <?php if (!empty($recentMessages)): ?>
            <div style="margin-top: 2rem;">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h2 class="card-title">Recent Analyses</h2>
                        <a href="index.php?page=history" class="btn btn-secondary btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="message-list">
                            <?php foreach ($recentMessages as $msg): ?>
                            <div class="message-item">
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
                                    <div class="message-text"><?= sanitize(substr($msg['content'], 0, 100)) ?>...</div>
                                    <div class="message-meta">
                                        <span class="badge <?= $msg['is_spam'] ? 'badge-spam' : 'badge-ham' ?>">
                                            <?= $msg['is_spam'] ? 'Spam' : 'Legitimate' ?>
                                        </span>
                                        <span><?= round($msg['spam_probability'] * 100, 1) ?>% confidence</span>
                                        <span><?= date('M j, g:i A', strtotime($msg['analyzed_at'])) ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        document.getElementById('analyzeForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = document.getElementById('message').value.trim();
            if (!message) return;
            
            const btn = document.getElementById('analyzeBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const resultContainer = document.getElementById('resultContainer');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            
            // Show loading state
            btn.disabled = true;
            btnText.textContent = 'Analyzing...';
            btnSpinner.style.display = 'inline-block';
            
            try {
                const formData = new FormData();
                formData.append('message', message);
                
                const response = await fetch('index.php?page=analyze', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.error) {
                    alert(result.error);
                    return;
                }
                
                // Display result
                resultPlaceholder.style.display = 'none';
                resultContainer.style.display = 'block';
                
                const isSpam = result.is_spam;
                const probability = (result.spam_probability * 100).toFixed(1);
                
                let indicatorsHtml = '';
                if (result.indicators && result.indicators.length > 0) {
                    indicatorsHtml = `
                        <div style="margin-top: 1rem;">
                            <strong style="font-size: 0.875rem;">Detected Indicators:</strong>
                            <div class="indicators-list">
                                ${result.indicators.map(ind => `
                                    <span class="indicator-tag">${ind.term}</span>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
                
                resultContainer.innerHTML = `
                    <div class="result-box ${isSpam ? 'result-spam' : 'result-ham'}">
                        <div class="result-title">
                            ${isSpam ? `
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="15" y1="9" x2="9" y2="15"/>
                                    <line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                Spam Detected
                            ` : `
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Legitimate Message
                            `}
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; font-size: 0.875rem; margin-bottom: 0.5rem;">
                            <span>Spam Probability</span>
                            <strong>${probability}%</strong>
                        </div>
                        <div class="probability-bar">
                            <div class="probability-fill ${isSpam ? 'spam' : 'ham'}" style="width: ${probability}%"></div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem; font-size: 0.875rem;">
                            <div>
                                <span style="color: var(--text-muted);">Confidence:</span>
                                <strong style="text-transform: capitalize;">${result.confidence}</strong>
                            </div>
                            <div>
                                <span style="color: var(--text-muted);">Method:</span>
                                <strong>${result.analysis_method}</strong>
                            </div>
                        </div>
                        
                        ${indicatorsHtml}
                    </div>
                `;
                
            } catch (error) {
                alert('An error occurred. Please try again.');
                console.error(error);
            } finally {
                btn.disabled = false;
                btnText.textContent = 'Analyze Message';
                btnSpinner.style.display = 'none';
            }
        });
    </script>
</body>
</html>
