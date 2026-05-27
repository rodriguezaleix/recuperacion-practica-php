CREATE DATABASE IF NOT EXISTS taquilla_parque;
USE taquilla_parque;

CREATE TABLE attractions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    maintenance TINYINT(1) DEFAULT 0,
    duration_minutes INT,
    min_height_cm INT,
    category VARCHAR(50)
);

CREATE TABLE ticket_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_email VARCHAR(255) NOT NULL,
    total DECIMAL(10, 2) DEFAULT 0.00,
    status ENUM('PENDING', 'COMPLETED', 'CANCELLED') DEFAULT 'PENDING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    ticket_type_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (ticket_type_id) REFERENCES ticket_types(id) ON DELETE RESTRICT
);

-- Datos de prueba: 10 Atracciones de Dinosaurios
INSERT INTO attractions (name, description, maintenance, duration_minutes, min_height_cm, category) VALUES
('T-Rex Kingdom', 'Encuentro cara a cara con el Rey de los Dinosaurios.', 0, 15, 120, 'Aventura Extrema'),
('Valle de los Braquiosaurios', 'Safari inolvidable a través del valle jurásico.', 1, 25, 90, 'Aventura Familiar'),
('Rápidos del Dilophosaurus', 'Rápidos con efectos de agua y luces.', 0, 20, 100, 'Aventura Acuática'),
('Vuelo del Pteranodon', 'Sobrevuela el parque en esta atracción colgante.', 0, 10, 110, 'Aventura Extrema'),
('Cueva del Triceratops', 'Paseo tranquilo por las cuevas subterráneas.', 0, 15, 0, 'Aventura Familiar'),
('Estampida Jurásica', 'Montaña rusa de alta velocidad por la selva.', 1, 5, 130, 'Aventura Extrema'),
('Laboratorio Genético', 'Recorrido educativo por el centro de creación.', 0, 30, 0, 'Educativo'),
('Nido del Velociraptor', 'Laberinto de terror con raptores acechando.', 0, 10, 120, 'Terror'),
('Tren Prehistórico', 'Tren panorámico que recorre todo el parque.', 0, 40, 0, 'Aventura Familiar'),
('Giro-esfera', 'Paseo seguro entre manadas de herbívoros.', 0, 20, 100, 'Aventura Familiar');

-- Datos de prueba: 3 Tipos de ticket
INSERT INTO ticket_types (name, price) VALUES
('Entrada General', 45.00),
('Entrada Infantil', 25.00),
('Entrada Senior (+65)', 35.00);
