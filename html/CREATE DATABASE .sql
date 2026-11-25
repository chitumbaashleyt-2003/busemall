
USE buse_marketplace;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Register the administrator as a normal user, then promote that account:
-- UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    shop_name VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
        ON DELETE SET NULL
);

USE buse_marketplace;

CREATE TABLE shops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(150),
    email VARCHAR(150),
    phone VARCHAR(20),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO shops (shop_name, description, location)
VALUES
('Palpo Store', 'Laptops, phones and electronic accessories', 'BUSE'),
('Grocery Shop', 'Groceries, food and household supplies', 'BUSE'),
('Clothes Shop', 'Clothing, shoes and fashion accessories', 'BUSE'),
('Masawo Shop', 'Locally manufactured finished products', 'BUSE');