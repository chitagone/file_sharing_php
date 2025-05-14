<?php
class Category {
    private $conn;
    private $table = "document_categories";

    public $id;
    public $name;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Category
    public function create() {
        $query = "INSERT INTO {$this->table} SET name=:name, description=:description";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        return $stmt->execute();
    }

    // Update Category
    public function update() {
        $query = "UPDATE {$this->table} SET name=:name, description=:description WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Delete Category
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Read Category
    public function read() {
        $query = "SELECT * FROM {$this->table} WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    // List Categories
    public function list() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
