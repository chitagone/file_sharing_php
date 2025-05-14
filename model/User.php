<?php
class User {
    private $conn;
    private $table = "users";

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create User
  public function create() {
    $query = "INSERT INTO {$this->table} SET name=:name, email=:email, password=:password";
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

    // Bind values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":password", $hashedPassword);

    if ($stmt->execute()) {
        // Generate a custom token (e.g., using HMAC with user email + current time)
        $this->id = $this->conn->lastInsertId();
        $secretKey = 'your_secret_key'; // Must match in your auth middleware
        $timestamp = time();
        $rawToken = $this->email . '|' . $timestamp;
        $signature = hash_hmac('sha256', $rawToken, $secretKey);
        $token = base64_encode($rawToken . '|' . $signature);

        return [
            "message" => "User created successfully.",
            "token" => $token
        ];
    }

    return false;
}

    // Read User
    public function read() {
        $query = "SELECT * FROM {$this->table} WHERE id=:id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Update User
    public function update() {
        $query = "UPDATE {$this->table} SET name=:name, email=:email WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Delete User
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

  public function login() {
    $query = "SELECT id, name, email, password FROM {$this->table} WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);

    // Clean and bind email
    $this->email = htmlspecialchars(strip_tags($this->email));
    $stmt->bindParam(":email", $this->email);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the password is correct
        if (password_verify($this->password, $row['password'])) {
            // Generate token
            $secretKey = 'your_secret_key'; // Same as used in register
            $timestamp = time();
            $rawToken = $row['email'] . '|' . $timestamp;
            $signature = hash_hmac('sha256', $rawToken, $secretKey);
            $token = base64_encode($rawToken . '|' . $signature);

            return [
                "message" => "Login successful.",
                "token" => $token,
                "user" => [
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "email" => $row['email']
                ]
            ];
        } else {
            return [
                "message" => "Invalid password."
            ];
        }
    }

    return [
        "message" => "User not found."
    ];
}

}






