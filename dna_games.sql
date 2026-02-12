CREATE DATABASE IF NOT EXISTS dna_games;
USE dna_games;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone_number VARCHAR(20),
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    date_of_birth DATE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    holder_name VARCHAR(100) NOT NULL,
    card_number_last4 CHAR(4) NOT NULL,
    expiration_date VARCHAR(7) NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
	games_purchased TEXT NOT NULL
);

select * from users;
select * from purchases;


