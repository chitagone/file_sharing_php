<?php
class Document {
    private $conn;
    private $table = "documents";

    public $id;
    public $user_id;
    public $title;
    public $file_name;
    public $file_path;
    public $file_type;
    public $file_size;
    public $category_id;
    public $is_public;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Document
    public function create() {
        $query = "INSERT INTO {$this->table} SET user_id=:user_id, title=:title, file_name=:file_name, file_path=:file_path, file_type=:file_type, file_size=:file_size, category_id=:category_id, is_public=:is_public";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":file_name", $this->file_name);
        $stmt->bindParam(":file_path", $this->file_path);
        $stmt->bindParam(":file_type", $this->file_type);
        $stmt->bindParam(":file_size", $this->file_size);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":is_public", $this->is_public);
        return $stmt->execute();
    }

    // Read Document
    public function read() {
        $query = "SELECT * FROM {$this->table} WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Update Document
    public function update() {
        $query = "UPDATE {$this->table} SET title=:title, file_name=:file_name, file_path=:file_path, file_type=:file_type, file_size=:file_size, category_id=:category_id, is_public=:is_public WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":file_name", $this->file_name);
        $stmt->bindParam(":file_path", $this->file_path);
        $stmt->bindParam(":file_type", $this->file_type);
        $stmt->bindParam(":file_size", $this->file_size);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":is_public", $this->is_public);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Delete Document
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Search Documents
    public function search($keyword) {
    // Prepare the SQL query with LIKE to search both title and file_name
    $query = "SELECT id, user_id, title, file_name, file_path, file_type, file_size, category_id, is_public 
              FROM " . $this->table . "
              WHERE title LIKE :keyword OR file_name LIKE :keyword";

    $stmt = $this->conn->prepare($query);

    // Add wildcard characters for partial matching
    $keyword = "%" . $keyword . "%";
    $stmt->bindParam(":keyword", $keyword);

    $stmt->execute();
    return $stmt;
}

}
