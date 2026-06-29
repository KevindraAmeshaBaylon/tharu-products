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
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                return "Please enter both your assigned username and access password.";
            }

            $user = $this->userModel->getUserByUsername($username);

            if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
                
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];

                // 🔄 CORRECTED STRUCTURAL REDIRECT DIRECTORIES FOR TARGET CODES
                switch ($user['role']) {
                    case 'Owner':
                        header("Location: ../dashboards/owner.php");
                        exit();
                    case 'Accountant':
                        header("Location: ../dashboards/accountant.php");
                        exit();
                    case 'Stock Supervisor':
                        header("Location: ../dashboards/stock_supervisor.php");
                        exit();
                    case 'Sales Supervisor':
                        header("Location: ../dashboards/sales_supervisor.php");
                        exit();
                    case 'Driver':
                        header("Location: ../dashboards/driver.php");
                        exit();
                    case 'Worker':
                        header("Location: ../dashboards/worker.php");
                        exit();
                    default:
                        header("Location: ../index.php");
                        exit();
                }
            } else {
                return "Invalid credentials. Please verify your administrative login layout.";
            }
        }
        return null;
    }
}