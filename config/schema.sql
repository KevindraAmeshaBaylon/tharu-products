-- config/schema.sql
CREATE DATABASE IF NOT EXISTS tharu_products_db;
USE tharu_products_db;

-- 1. USERS TABLE (Handles Owner, Accountant, Supervisors, Drivers, Workers)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    role ENUM('Owner', 'Accountant', 'Stock Supervisor', 'Sales Supervisor', 'Driver', 'Worker') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. CUSTOMERS TABLE (Farms like Delmo, Maxies, or small independent farms)
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farm_name VARCHAR(150) NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);