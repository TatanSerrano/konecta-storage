CREATE DATABASE konecta_storage;

use konecta_storage;

CREATE TABLE Product (
    product_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    referencia VARCHAR(30) NOT NULL,
    precio INT(10) NOT NULL,
    peso INT(4) NOT NULL,
    categoria VARCHAR(30),
    stock INT(7) UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Sale (
    sale_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quantity INT(4) NOT NULL,
    product_id INT(11) UNSIGNED,
    FOREIGN KEY (product_id) REFERENCES Product(product_id)
);