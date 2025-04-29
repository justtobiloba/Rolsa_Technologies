
-- Database: rolsa


CREATE DATABASE IF NOT EXISTS rolsa;
USE rolsa;

-- Table structure for users

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL ,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(255),
    token_expiry DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for services

CREATE TABLE IF NOT EXISTS services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    service_type ENUM('solar', 'ev_charging', 'smart_home') NOT NULL,
    description TEXT NOT NULL,
   
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for carbon_footprints

CREATE TABLE IF NOT EXISTS carbon_footprints (
    footprint_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    energy_usage DECIMAL(10,2) NOT NULL,
    transportation DECIMAL(10,2) NOT NULL,
    lifestyle_score INT NOT NULL,
    total_footprint DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for appointments

CREATE TABLE IF NOT EXISTS appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    scheduled_date DATETIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Services Data

-- Insert the 3 service types
INSERT INTO services (name, description) VALUES
('Solar Panel', 'Solar panel systems and energy solutions.'),
('EV Charging Station', 'Electric vehicle charging station installation and support.'),
('Smart Home Energy', 'Smart home energy systems consultation and installation.');
-- Indexes for better performance

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_services_type ON services(service_type);
CREATE INDEX idx_appointments_date ON appointments(scheduled_date);


-- Test User (password: test1234)


INSERT INTO users 
    (name, email, password)
VALUES
    ('Test User', 'test@rolsa.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

ALTER TABLE appointments ADD COLUMN scheduled_time TIME NOT NULL AFTER scheduled_date;


-- Consultation table
CREATE TABLE IF NOT EXISTS consultations (
    consultation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    scheduled_date DATE NOT NULL,
    scheduled_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE
);

-- Installation table
CREATE TABLE IF NOT EXISTS installations (
    installation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    scheduled_date DATE NOT NULL,
    scheduled_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE
);
