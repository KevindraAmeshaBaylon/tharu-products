-- config/schema.sql
CREATE DATABASE IF NOT EXISTS tharu_products_db;
USE tharu_products_db;

-- =========================================================================
-- 1. INTERNAL STAFF SYSTEM USERS
-- =========================================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Storing simple strings for immediate local testing
    full_name VARCHAR(150) NOT NULL,
    role ENUM('Owner', 'Accountant', 'Stock Supervisor', 'Sales Supervisor', 'Driver', 'Worker') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================================
-- 2. EXTERNAL WHOLESALE CLIENT CUSTOMERS (Farms / Large Outlets)
-- =========================================================================
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farm_name VARCHAR(150) NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================================
-- 3. STAFF ATTENDANCE PAYROLL LEDGER 
-- =========================================================================
CREATE TABLE IF NOT EXISTS payroll_slips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    month_year VARCHAR(7) NOT NULL, -- Format: YYYY-MM
    base_rate DECIMAL(10,2) NOT NULL,
    days_present INT NOT NULL,
    leaves_taken INT NOT NULL,
    ot_increment_blocks INT DEFAULT 0,
    holiday_bonus DECIMAL(10,2) DEFAULT 0.00,
    net_payable_wage DECIMAL(10,2) NOT NULL,
    processed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================================
-- 4. RAW SUPPLIER CONTRACTS
-- =========================================================================
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(150) NOT NULL,
    material_type VARCHAR(100) NOT NULL,
    assigned_supervisor_id INT NULL,
    FOREIGN KEY (assigned_supervisor_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================================================================
-- 5. STOCK ENTRY LEDGER & TRACEABILITY SYSTEM
-- =========================================================================
CREATE TABLE IF NOT EXISTS stock_intakes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    volume_metric_tons DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    batch_reference_code VARCHAR(50) NOT NULL UNIQUE,
    intake_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================================
-- 6. FEED PRODUCTS INVENTORY BALANCE LEDGER
-- =========================================================================
CREATE TABLE IF NOT EXISTS product_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL UNIQUE,
    unit_price_lkr DECIMAL(10,2) NOT NULL,
    beginning_stock INT NOT NULL DEFAULT 0,
    purchases_intake INT NOT NULL DEFAULT 0,
    sales_output INT NOT NULL DEFAULT 0,
    closing_stock INT NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================================
-- 7. CUSTOMER ORDERS LEDGER PIPELINE
-- =========================================================================
CREATE TABLE IF NOT EXISTS client_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    order_quantity INT NOT NULL,
    total_invoice_lkr DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    order_status ENUM('Pending Verification', 'Dispatched for Delivery', 'Delivered & Completed') DEFAULT 'Pending Verification',
    assigned_driver_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product_inventory(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_driver_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================================================================
-- 8. INITIAL PRE-SEEDED SYSTEM ACCOUNTS (PASSWORD IS SIMPLE '12345' FOR VIVA TESTING)
-- =========================================================================
INSERT INTO users (username, password, full_name, role) VALUES 
('owner_tharu', '12345', 'Tharu Corporate Owner', 'Owner'),
('accountant_desk', '12345', 'M. N. Perera', 'Accountant'),
('stock_sup', '12345', 'A. Silva (Stock Dept)', 'Stock Supervisor'),
('sales_sup', '12345', 'K. Fernando (Sales Dept)', 'Sales Supervisor'),
('driver_lorry4', '12345', 'Heavy Fleet Driver #04', 'Driver'),
('worker_station12', '12345', 'Line Worker #12', 'Worker');

INSERT INTO product_inventory (product_name, unit_price_lkr, beginning_stock, purchases_intake, sales_output, closing_stock) VALUES
('Premium Chicken Feed', 8500.00, 1200, 450, 300, 1350),
('Dairy Cow Feed', 9200.00, 800, 200, 150, 850),
('Swine Growth Feed', 7900.00, 500, 100, 50, 550);