<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM products ORDER BY nom");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur Product::getAll(): " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
    $stmt = $this->db->prepare("
        INSERT INTO products (nom, description, prix, stock) 
        VALUES (:nom, :description, :prix, :stock)
    ");
    return $stmt->execute([
        ':nom' => $data['nom'],
        ':description' => $data['description'],
        ':prix' => $data['prix'],
        ':stock' => $data['stock']
    ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE products 
            SET nom = ?, description = ?, prix = ?, stock = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['nom'],
            $data['description'],
            $data['prix'],
            $data['stock'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}