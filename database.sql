-- Database structure for NovaDrive Rent a Car
CREATE DATABASE IF NOT EXISTS novadrive CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE novadrive;

-- Users table with roles
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products/Cars table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price_per_day DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100),
    transmission VARCHAR(50),
    image_path VARCHAR(500),
    pdf_path VARCHAR(500),
    created_by INT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- News table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(500),
    pdf_path VARCHAR(500),
    created_by INT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    read_status BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- About page content (dynamic content)
CREATE TABLE IF NOT EXISTS about_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(500),
    display_order INT DEFAULT 0,
    created_by INT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Home page slider content
CREATE TABLE IF NOT EXISTS slider_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(500) NOT NULL,
    link_url VARCHAR(500),
    display_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Administrator', 'admin@novadrive.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample products
INSERT INTO products (name, description, price_per_day, category, transmission, image_path, created_by) VALUES
('Range Rover Evoque', 'Komoditet i lartë, 4x4, asistencë premium.', 140.00, 'SUV', 'Automatike', 'IMG/2025_land-rover_range-rover_4dr-suv_p530-autobiography_fq_oem_1_1280.avif', 1),
('BMW 5 Series', 'Teknologji e avancuar, komoditet biznesi.', 110.00, 'Sedan', 'Automatike', 'IMG/TopGear - First Drive - BMW 5 Series 2024-031.jpg', 1),
('VW Golf 8', 'Efiçente, kompakte dhe perfekte për qytet.', 55.00, 'City', 'Manuale', 'IMG/white-golf-r-image.png', 1),
('Audi Q3', 'SUV kompakt me asistencë adaptive cruise.', 95.00, 'SUV', 'Automatike', 'IMG/aNXN0p5xUNkB1Hsj_audi-q3-2025-lhd-header.avif', 1);

-- Insert sample news
INSERT INTO news (title, content, image_path, created_by) VALUES
('Flota e re 2025', 'Ne kemi shtuar makinat më të reja në flotën tonë për vitin 2025. Tani ofrojmë më shumë zgjedhje dhe teknologji më të avancuar.', 'IMG/RR_EVQ_24MY_DYNAMIC_HSE_210623_03.jpg', 1),
('Oferta speciale për dimër', 'Rezervoni një makinë për më shumë se 7 ditë dhe merrni 15% zbritje. Oferta vlen deri në fund të muajit.', 'IMG/DB2024AU00109_web_1600.jpg', 1);

-- Insert sample about content
INSERT INTO about_content (section_title, content, display_order, created_by) VALUES
('Teknologji', 'Proces i digjitalizuar: firmos online, track-on-time dhe verifikim dokumentesh në sekonda.', 1, 1),
('Siguri', 'Sigurim i plotë, asistencë rrugore dhe inspektime periodike të çdo mjeti.', 2, 1),
('Fleksibilitet', 'Paketa ditore, javore ose mujore. Mundësi ndërrimi modeli gjatë kontratës.', 3, 1);

-- Insert sample slider content
INSERT INTO slider_content (title, description, image_path, display_order) VALUES
('Premium, i shpejtë, 24/7', 'Gati për udhëtimin tënd të radhës? Merr makinën në pak minuta.', 'IMG/2025_land-rover_range-rover_4dr-suv_p530-autobiography_fq_oem_1_1280.avif', 1),
('Flotë 2023-2024', 'Fleksibilitet maksimal, çmime transparente dhe asistencë rrugore kudo në vend.', 'IMG/RR_EVQ_24MY_DYNAMIC_HSE_210623_03.jpg', 2),
('Sigurim i plotë', 'Rezervo online dhe nise aventurën me siguri të plotë.', 'IMG/aNXN0p5xUNkB1Hsj_audi-q3-2025-lhd-header.avif', 3);

Databaza