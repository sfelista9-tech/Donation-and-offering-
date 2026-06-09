-- Create Smart Donation System Database
CREATE DATABASE IF NOT EXISTS smart_donation_system;
USE smart_donation_system;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Members Table
CREATE TABLE IF NOT EXISTS members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_no VARCHAR(50) UNIQUE NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female', 'Other'),
    phone VARCHAR(20),
    address TEXT,
    email VARCHAR(100),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    joined_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Offerings Table
CREATE TABLE IF NOT EXISTS offerings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    offering_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

-- Donations Table
CREATE TABLE IF NOT EXISTS donations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    purpose VARCHAR(255),
    donor_name VARCHAR(100),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

-- Insert Sample Admin User (Password: admin123)
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$YOIz0iH1bB8Z6Z6Z6Z6Z6eZ6Z6Z6Z6Z6Z6Z6Z6Z6Z6Z6Z6Z6Z6Z6Z6', 'admin@donation.com', 'admin');

-- Insert Sample Members
INSERT INTO members (member_no, fullname, gender, phone, address, email) VALUES
('M001', 'John Doe', 'Male', '0755123456', '123 Main St', 'john@email.com'),
('M002', 'Jane Smith', 'Female', '0755234567', '456 Oak Ave', 'jane@email.com'),
('M003', 'Peter Johnson', 'Male', '0755345678', '789 Pine Rd', 'peter@email.com');

-- Insert Sample Offerings
INSERT INTO offerings (member_id, amount, description) VALUES
(1, 50000, 'Weekly offering'),
(2, 75000, 'Monthly offering'),
(3, 100000, 'Special offering');

-- Insert Sample Donations
INSERT INTO donations (member_id, amount, purpose, donor_name) VALUES
(1, 200000, 'Church renovation', 'John Doe'),
(2, 300000, 'Community project', 'Jane Smith'),
(3, 250000, 'Charity fund', 'Peter Johnson');
