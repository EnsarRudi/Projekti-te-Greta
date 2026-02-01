<?php
require_once __DIR__ . '/Database.php';

class Contact {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function submitMessage($name, $email, $message) {
        // Validate input
        if (empty($name) || empty($email) || empty($message)) {
            return ['success' => false, 'message' => 'Të gjitha fushat duhen plotësuar.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email i pavlefshëm.'];
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            return ['success' => true, 'message' => 'Mesazhi u dërgua me sukses!'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Gabim në dërgim: ' . $e->getMessage()];
        }
    }

    public function getAll($unreadOnly = false) {
        $sql = "SELECT * FROM contact_messages";
        if ($unreadOnly) {
            $sql .= " WHERE read_status = FALSE";
        }
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function markAsRead($id) {
        $stmt = $this->conn->prepare("UPDATE contact_messages SET read_status = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM contact_messages WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getUnreadCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE read_status = FALSE");
        $result = $stmt->fetch();
        return $result['count'];
    }
}

