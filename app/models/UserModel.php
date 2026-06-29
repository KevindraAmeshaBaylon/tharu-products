<?php
// app/models/UserModel.php

class UserModel {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to check user credentials for login validation
    public function authenticate($username, $password) {
        $query = "SELECT id, username, password, full_name, role FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Simple string matching match check
            if($password === $row['password']) {
                return $row; // Authentication successful, returns user details array
            }
        }
        return false; // Authentication failed
    }
}