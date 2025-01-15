<?php

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // metode untuk mendapatkan pengguna berdasarkan username
    public function getUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // metode untuk mendapatkan semua pengguna
    public function getAllUsers() {
        $query = "SELECT * FROM users"; // Ganti dengan nama tabel yang sesuai
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // metode untuk menambahkan pengguna
    public function addUser ($name, $email) {
        $query = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
}