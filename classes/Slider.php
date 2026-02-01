<?php
require_once __DIR__ . '/Database.php';

class Slider {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getAll($activeOnly = true) {
        $sql = "SELECT * FROM slider_content";
        if ($activeOnly) {
            $sql .= " WHERE active = TRUE";
        }
        $sql .= " ORDER BY display_order ASC";
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM slider_content WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO slider_content (title, description, image_path, link_url, display_order, active) 
                VALUES (?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['image_path'],
            $data['link_url'] ?? null,
            $data['display_order'] ?? 0,
            $data['active'] ?? true
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE slider_content SET 
                title = ?, description = ?, image_path = ?, link_url = ?, display_order = ?, active = ?
                WHERE id = ?");
        
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['image_path'],
            $data['link_url'] ?? null,
            $data['display_order'] ?? 0,
            $data['active'] ?? true,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM slider_content WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

