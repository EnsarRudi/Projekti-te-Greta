<?php
require_once __DIR__ . '/Database.php';

class News {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getAll($limit = null) {
        $sql = "SELECT n.*, 
                u1.name as created_by_name, 
                u2.name as updated_by_name 
                FROM news n
                LEFT JOIN users u1 ON n.created_by = u1.id
                LEFT JOIN users u2 ON n.updated_by = u2.id
                ORDER BY n.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT n.*, 
                u1.name as created_by_name, 
                u2.name as updated_by_name 
                FROM news n
                LEFT JOIN users u1 ON n.created_by = u1.id
                LEFT JOIN users u2 ON n.updated_by = u2.id
                WHERE n.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO news (title, content, image_path, pdf_path, created_by) 
                VALUES (?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $data['image_path'] ?? null,
            $data['pdf_path'] ?? null,
            $userId
        ]);
    }

    public function update($id, $data, $userId) {
        $stmt = $this->conn->prepare("UPDATE news SET 
                title = ?, content = ?, image_path = ?, pdf_path = ?, updated_by = ?
                WHERE id = ?");
        
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $data['image_path'] ?? null,
            $data['pdf_path'] ?? null,
            $userId,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM news WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

