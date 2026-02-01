<?php
require_once __DIR__ . '/Database.php';

class Product {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getAll($limit = null) {
        $sql = "SELECT p.*, 
                u1.name as created_by_name, 
                u2.name as updated_by_name 
                FROM products p
                LEFT JOIN users u1 ON p.created_by = u1.id
                LEFT JOIN users u2 ON p.updated_by = u2.id
                ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT p.*, 
                u1.name as created_by_name, 
                u2.name as updated_by_name 
                FROM products p
                LEFT JOIN users u1 ON p.created_by = u1.id
                LEFT JOIN users u2 ON p.updated_by = u2.id
                WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, description, price_per_day, category, transmission, image_path, pdf_path, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['price_per_day'],
            $data['category'] ?? null,
            $data['transmission'] ?? null,
            $data['image_path'] ?? null,
            $data['pdf_path'] ?? null,
            $userId
        ]);
    }

    public function update($id, $data, $userId) {
        $stmt = $this->conn->prepare("UPDATE products SET 
                name = ?, description = ?, price_per_day = ?, category = ?, 
                transmission = ?, image_path = ?, pdf_path = ?, updated_by = ?
                WHERE id = ?");
        
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['price_per_day'],
            $data['category'] ?? null,
            $data['transmission'] ?? null,
            $data['image_path'] ?? null,
            $data['pdf_path'] ?? null,
            $userId,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

