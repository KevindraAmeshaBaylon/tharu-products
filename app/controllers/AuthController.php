<?php
// app/controllers/AuthController.php

class AuthController {
    private $db;
    private $userModel;

    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
        include_once __DIR__ . '/../models/UserModel.php';
        $this->userModel = new UserModel($this->db);
    }

    public function login() {
        // Start session handling safely
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $error = "";

        // Process submission form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if (!empty($username) && !empty($password)) {
                $user = $this->userModel->authenticate($username, $password);

                if ($user) {
                    // Set secure session parameters 
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];

                    // Strict role-based routing map matching user options
                    switch ($user['role']) {
                        case 'Owner':
                            header("Location: ../views/dashboards/owner.php");
                            break;
                        case 'Accountant':
                            header("Location: ../views/dashboards/accountant.php");
                            break;
                        case 'Stock Supervisor':
                            header("Location: ../views/dashboards/stock_supervisor.php");
                            break;
                        case 'Sales Supervisor':
                            header("Location: ../views/dashboards/sales_supervisor.php");
                            break;
                        case 'Driver':
                            header("Location: ../views/dashboards/driver.php");
                            break;
                        case 'Worker':
                            header("Location: ../views/dashboards/worker.php");
                            break;
                        default:
                            header("Location: ../views/index.php");
                            break;
                    }
                    exit();
                } else {
                    $error = "Invalid username or password!";
                }
            } else {
                $error = "Please fill in all credentials.";
            }
        }
        return $error;
    }
}