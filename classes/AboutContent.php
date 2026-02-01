<?php
require_once __DIR__ . '/Database.php';

class AboutContent {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM about_content ORDER BY display_order ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM about_content WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO about_content (section_title, content, image_path, display_order, created_by) 
                VALUES (?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $data['section_title'],
            $data['content'],
            $data['image_path'] ?? null,
            $data['display_order'] ?? 0,
            $userId
        ]);
    }

    public function update($id, $data, $userId) {
        $stmt = $this->conn->prepare("UPDATE about_content SET 
                section_title = ?, content = ?, image_path = ?, display_order = ?, updated_by = ?
                WHERE id = ?");
        
        return $stmt->execute([
            $data['section_title'],
            $data['content'],
            $data['image_path'] ?? null,
            $data['display_order'] ?? 0,
            $userId,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM about_content WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

