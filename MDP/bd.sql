CREATE DATABASE IdentificationAuthentification;identificationauthentification

USE IdentificationAuthentification;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
