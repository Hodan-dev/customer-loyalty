CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS ml_predictions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    loyalty_level ENUM('Low', 'Medium', 'High') DEFAULT 'Low',
    recommended_discount DECIMAL(5,2) DEFAULT 0.00,
    special_card ENUM('None', 'Silver', 'Gold', 'VIP') DEFAULT 'None',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    code VARCHAR(20) NOT NULL,
    discount_percent DECIMAL(5,2) NOT NULL,
    status ENUM('Active', 'Used', 'Expired') DEFAULT 'Active',
    expiry_date DATE NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
);
