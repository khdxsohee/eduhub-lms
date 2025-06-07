<?php
// app/models/User.php

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        return $row;
    }

    // Register a new user
    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); // Hashed password
        $this->db->bind(':role', $data['role']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($email, $password) {
        $user = $this->findUserByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    // Get all instructors with pending status
    public function getPendingInstructors() {
        $this->db->query("SELECT * FROM users WHERE role = 'instructor' AND status = 'pending'");
        return $this->db->resultSet();
    }

    // Update user status
    public function updateStatus($userId, $status) {
        $this->db->query('UPDATE users SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    // Get all users
    public function getAllUsers() {
        $this->db->query('SELECT * FROM users');
        return $this->db->resultSet();
    }
}
?>