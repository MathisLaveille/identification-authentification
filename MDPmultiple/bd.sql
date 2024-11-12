CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    password VARCHAR(255),
    date_of_birth DATE,
    place_of_birth VARCHAR(100),
    gender CHAR(1),
    address VARCHAR(255),
    phone_number VARCHAR(20),
    email_address VARCHAR(100),
    age INT
);
