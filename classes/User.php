<?php
require_once __DIR__ . '/Database.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function register($name, $email, $password) {
        // Validate input
        if (empty($name) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Të gjitha fushat duhen plotësuar.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email i pavlefshëm.'];
        }

        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'Fjalëkalimi duhet të jetë të paktën 8 karaktere.'];
        }

        // Check if email exists
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email-i tashmë ekziston.'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        try {
            $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$name, $email, $hashedPassword]);
            return ['success' => true, 'message' => 'Regjistrimi u krye me sukses!'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Gabim në regjistrim: ' . $e->getMessage()];
        }
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email dhe fjalëkalimi duhen plotësuar.'];
        }

        $stmt = $this->conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            return ['success' => true, 'message' => 'Hyrja u krye me sukses!', 'role' => $user['role']];
        }

        return ['success' => false, 'message' => 'Email ose fjalëkalim i pasaktë.'];
    }

    public function logout() {
        session_unset();
        session_destroy();
        return ['success' => true, 'message' => 'Dilje e suksesshme.'];
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role']
        ];
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}

