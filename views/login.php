<?php
/**
 * Login Page View
 */
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SpamShield</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card card">
            <div class="card-body">
                <div class="auth-header">
                    <a href="index.php" class="logo" style="justify-content: center; margin-bottom: 1.5rem;">
                        <div class="logo-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <span>SpamShield</span>
                    </a>
                    <h1 class="auth-title">Welcome back</h1>
                    <p class="auth-subtitle">Sign in to your account to continue</p>
                </div>

                <?php if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>">
                        <?= sanitize($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=login">
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" required placeholder="you@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-input" required placeholder="Enter your password">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
                </form>

                <div class="auth-footer">
                    Don't have an account? <a href="index.php?page=register">Create one</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
