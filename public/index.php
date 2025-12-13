<?php
/**
 * SpamShield - Main Entry Point (Front Controller)
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/SpamDetector.php';
require_once __DIR__ . '/../classes/Message.php';

// Get the requested page
$page = $_GET['page'] ?? 'home';

// Handle AJAX requests for spam analysis
if ($page === 'analyze' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    if (!isLoggedIn()) {
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    $content = $_POST['message'] ?? '';
    
    if (empty($content)) {
        echo json_encode(['error' => 'Message is required']);
        exit;
    }
    
    $detector = new SpamDetector();
    $result = $detector->analyze($content);
    
    // Save to database
    $messageModel = new Message();
    $messageModel->save(getCurrentUserId(), $content, $result);
    
    echo json_encode($result);
    exit;
}

// Handle authentication
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $result = $user->login($_POST['email'] ?? '', $_POST['password'] ?? '');
    
    if ($result['success']) {
        redirect('index.php?page=dashboard');
    } else {
        setFlash('error', $result['message']);
        redirect('index.php?page=login');
    }
}

if ($page === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $result = $user->register(
        $_POST['username'] ?? '',
        $_POST['email'] ?? '',
        $_POST['password'] ?? ''
    );
    
    if ($result['success']) {
        setFlash('success', 'Registration successful! Please login.');
        redirect('index.php?page=login');
    } else {
        setFlash('error', $result['message']);
        redirect('index.php?page=register');
    }
}

if ($page === 'logout') {
    $user = new User();
    $user->logout();
    redirect('index.php');
}

// Route to appropriate view
switch ($page) {
    case 'login':
        if (isLoggedIn()) {
            redirect('index.php?page=dashboard');
        }
        include __DIR__ . '/../views/login.php';
        break;
        
    case 'register':
        if (isLoggedIn()) {
            redirect('index.php?page=dashboard');
        }
        include __DIR__ . '/../views/register.php';
        break;
        
    case 'dashboard':
        if (!isLoggedIn()) {
            redirect('index.php?page=login');
        }
        include __DIR__ . '/../views/dashboard.php';
        break;
        
    case 'history':
        if (!isLoggedIn()) {
            redirect('index.php?page=login');
        }
        include __DIR__ . '/../views/history.php';
        break;
        
    default:
        include __DIR__ . '/../views/home.php';
        break;
}
