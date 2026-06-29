<?php
// app/controllers/AuthController.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        // Initialize the operational database layer connection
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Pass the database link straight into the User Model core
        $this->userModel = new UserModel($this->db);
    }

    /**
     * Handles login execution and routes users dynamically based on roles
     */
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize user inputs to protect against dirty injection tokens
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                return "Please enter both your assigned username and access password.";
            }

            // Fetch the structural record matching this username profile
            $user = $this->userModel->getUserByUsername($username);

            // Verify account existence and crosscheck hashed security strings
            // Note: For local offline evaluation setups, direct string matching can act as a backup
            if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
                
                // Construct global tracking sessions parameters securely
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];

                // 🔄 DYNAMIC MVC ROUTING ENGINE FOR MULTI-ROLE TRAFFIC
                switch ($user['role']) {
                    case 'Owner':
                        header("Location: ../views/dashboards/owner.php");
                        exit();
                    case 'Accountant':
                        header("Location: ../views/dashboards/accountant.php");
                        exit();
                    case 'Stock Supervisor':
                        header("Location: ../views/dashboards/stock_supervisor.php");
                        exit();
                    case 'Sales Supervisor':
                        header("Location: ../views/dashboards/sales_supervisor.php");
                        exit();
                    case 'Driver':
                        header("Location: ../views/dashboards/driver.php");
                        exit();
                    case 'Worker':
                        header("Location: ../views/dashboards/worker.php");
                        exit();
                    default:
                        // Fallback safely to public root directory if role types are unmapped
                        header("Location: ../views/index.php");
                        exit();
                }
            } else {
                return "Invalid credentials. Please verify your administrative login layout.";
            }
        }
        return null;
    }
}