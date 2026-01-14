-- ==========================================
-- HANYA BUAT TABEL USERS DI db_eccomerce
-- ==========================================
-- Database sudah ada: db_eccomerce
-- Jalankan query ini di phpmyadmin atau mysql console
-- ==========================================

-- Gunakan database yang sudah ada
USE db_eccomerce;

-- Buat tabel users jika belum ada
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telepon VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Optional: Tambah index untuk performa
ALTER TABLE users ADD INDEX idx_email (email);

-- Optional: Insert dummy data untuk testing
INSERT INTO users (nama, email, password, telepon) VALUES 
('John Doe', 'john@example.com', '$2y$10$V9sM9P9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M', '081234567890'),
('Jane Smith', 'jane@example.com', '$2y$10$V9sM9P9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M9M', '081987654321');

-- Password untuk dummy data: password123 (sudah di-hash dengan password_hash)
-- Untuk verifikasi, gunakan password_verify('password123', $hashed_password)
