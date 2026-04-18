-- Create database
CREATE DATABASE IF NOT EXISTS parking_db;
USE parking_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Parking spots table
CREATE TABLE IF NOT EXISTS parking_spots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    spot_number VARCHAR(10) UNIQUE NOT NULL,
    status ENUM('available', 'occupied') DEFAULT 'available'
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    spot_id INT NOT NULL,
    start_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    end_time DATETIME NULL,
    status ENUM('active', 'completed') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (spot_id) REFERENCES parking_spots(id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$examplehashedpassword', 'admin'),  -- Password: admin123 (hashed)
('user1', '$2y$10$examplehashedpassword2', 'user');  -- Password: user123

INSERT INTO parking_spots (spot_number, status) VALUES 
('A1', 'available'),
('A2', 'available'),
('B1', 'occupied'),
('B2', 'available');