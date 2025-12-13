<?php
/**
 * User Class - Handles authentication and user management
 */

require_once __DIR__ . '/../config/config.php';

class User {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnection();
    }
    
    /**
     * Register a new user
     */
    public function register($username, $email, $password) {
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }
        
        // Check if username or email already exists
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }
        
        // Hash password and insert user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
        
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        
        try {
            $stmt->execute([$username, $email, $hashedPassword]);
            return ['success' => true, 'message' => 'Registration successful'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }
    
    /**
     * Login user
     */
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        $stmt = $this->pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        return ['success' => true, 'message' => 'Login successful'];
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_unset();
        session_destroy();
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
