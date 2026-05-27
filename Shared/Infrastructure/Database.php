<?php
    class DatabaseFactory {
        function createDatabaseConnection() : PDO {
            return new PDO('mysql:host=localhost;dbname=BankSouth', 'root', '');
        }

        function seedDatabase() : void {
            $conn = new PDO("mysql:host=localhost", "root", "");
            
            try {
                $conn->exec("CREATE DATABASE IF NOT EXISTS BankSouth");
                $conn->exec("USE BankSouth");

                $conn->exec("CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    firstname VARCHAR(64) NOT NULL,
                    lastname VARCHAR(64) NOT NULL   ,
                    phone VARCHAR(64) unique NOT NULL,
                    email VARCHAR(128) unique NOT NULL,
                    dateofbirth DATE NOT NULL,
                    userrole enum('customer', 'admin') NOT NULL DEFAULT 'customer',
                    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");

                $conn->exec("CREATE TABLE IF NOT EXISTS accounts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    kortnummer VARCHAR(255) UNIQUE NOT NULL,
                    pinkod VARCHAR(255) NOT NULL,
                    user_id INT NOT NULL,
                    account_type INT NOT NULL,
                    balance DECIMAL(15, 2) DEFAULT 0.00,
                    currency VARCHAR(3) NOT NULL,
                    status enum('active', 'inactive', 'closed') DEFAULT 'active',
                    login_attempts INT DEFAULT 0,
                    locked_until TIMESTAMP NULL DEFAULT NULL,
                    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");

                $conn->exec("CREATE TABLE IF NOT EXISTS account_types (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(64) unique NOT NULL,
                    description TEXT NOT NULL
                )");

                $conn->exec("CREATE TABLE transactions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    from_account_id INT NULL,
                    to_account_id INT NULL,
                    amount DECIMAL(12,2) NOT NULL,
                    type enum('Withdrawal', 'Deposit', 'Transfer'),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (from_account_id) REFERENCES accounts(id),
                    FOREIGN KEY (to_account_id) REFERENCES accounts(id)
                )");

                require_once __DIR__ . '/../Infrastructure/seeder.php';
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
?>