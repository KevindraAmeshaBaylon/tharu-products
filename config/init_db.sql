-- config/init_db.sql
-- Tharu & Products - Complete Database Initialization Blueprint

CREATE DATABASE IF NOT EXISTS `tharu_products_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tharu_products_db`;

-- --------------------------------------------------------
-- 1. Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` ENUM('Owner', 'Accountant', 'Stock Supervisor', 'Sales Supervisor', 'Driver', 'Worker') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 2. Table structure for table `suppliers`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `supplier_name` VARCHAR(150) NOT NULL,
  `material_type` VARCHAR(100) NOT NULL,
  `contact_number` VARCHAR(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 3. Table structure for table `product_inventory`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_inventory` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_name` VARCHAR(100) NOT NULL,
  `beginning_stock` INT DEFAULT 0,
  `purchases_intake` INT DEFAULT 0,
  `sales_output` INT DEFAULT 0,
  `closing_stock` INT GENERATED ALWAYS AS (beginning_stock + purchases_intake - sales_output) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 4. Table structure for table `customers`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `farm_name` VARCHAR(150) NOT NULL,
  `delivery_address` TEXT NOT NULL,
  `contact_person` VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 5. Table structure for table `client_orders`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `client_orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `order_quantity` INT NOT NULL,
  `order_status` ENUM('Pending Verification', 'Dispatched', 'Delivered') DEFAULT 'Pending Verification',
  `driver_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product_inventory`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`driver_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- ⚙️ SEED DATA GENERATOR ENGINE
-- --------------------------------------------------------

-- Insert Core Administrative & Field Teams
INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`) VALUES
(1, 'owner', 'owner123', 'Tharusha Perera', 'Owner'),
(2, 'accountant', 'acc123', 'M. N. Perera', 'Accountant'),
(3, 'stock', 'stock123', 'A. Silva', 'Stock Supervisor'),
(4, 'sales', 'sales123', 'K. Fernando', 'Sales Supervisor'),
(5, 'driver', 'driver123', 'Heavy Fleet Driver #04', 'Driver'),
(6, 'worker', 'worker123', 'Production Operator #12', 'Worker')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Insert Material Suppliers
INSERT INTO `suppliers` (`id`, `supplier_name`, `material_type`, `contact_number`) VALUES
(1, 'Polonnaruwa Rice Mills Ltd', 'Broken Rice Byproduct', '0272221111'),
(2, 'Marawila Coconut Processing Plant', 'Rice Bran Fine Powder', '0322254444')
ON DUPLICATE KEY UPDATE `supplier_name`=`supplier_name`;

-- Insert Systemic Inventory Baselines
INSERT INTO `product_inventory` (`id`, `product_name`, `beginning_stock`, `purchases_intake`, `sales_output`) VALUES
(1, 'Premium Chicken Feed', 1200, 450, 300),
(2, 'Dairy Cow Feed', 800, 200, 150),
(3, 'Swine Growth Feed', 500, 100, 50)
ON DUPLICATE KEY UPDATE `product_name`=`product_name`;

-- Insert Wholesale Wholesale Commercial Clients
INSERT INTO `customers` (`id`, `farm_name`, `delivery_address`, `contact_person`) VALUES
(1, 'Chilaw Premium Poultry Farm', 'No. 42, Puttalam Road, Chilaw', 'Mr. S. Fernando')
ON DUPLICATE KEY UPDATE `farm_name`=`farm_name`;

-- Insert a Sample Order Pre-assigned for Verification Routes
INSERT INTO `client_orders` (`id`, `customer_id`, `product_id`, `order_quantity`, `order_status`, `driver_id`) VALUES
(101, 1, 1, 50, 'Pending Verification', 5)
ON DUPLICATE KEY UPDATE `id`=`id`;