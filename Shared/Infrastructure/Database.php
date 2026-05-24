<?php
    class DatabaseFactory {
        function createDatabaseConnection() : PDO {
            return new PDO('mysql:host=localhost;dbname=BankSouth', 'root', '');
        }

        function seedDatabase() : Void {
            $conn = new PDO("mysql:host=localhost", "root", "");
            
            try {
                $conn->exec("CREATE DATABASE IF NOT EXISTS BankSouth");
                $conn->exec("USE BankSouth");

                $conn->exec("CREATE TABLE IF NOT EXISTS Users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    firstname VARCHAR(64) NOT NULL,
                    lastname VARCHAR(64) NOT NULL,
                    phone VARCHAR(64) NOT NULL,
                    email VARCHAR(128) NOT NULL,
                    dateofbirth DATE NOT NULL,
                    userrole enum('customer', 'admin') NOT NULL DEFAULT 'customer',
                    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");

                $conn->exec("CREATE TABLE IF NOT EXISTS Accounts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    kortnummer VARCHAR(255) NOT NULL,
                    pinkod VARCHAR(255) NOT NULL,
                    user_id INT NOT NULL,
                    account_type INT NOT NULL,
                    balance DECIMAL(15, 2) DEFAULT 0.00,
                    currency VARCHAR(3) NOT NULL,
                    status enum('active', 'inactive', 'closed') DEFAULT 'active',
                    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");

                $conn->exec("CREATE TABLE IF NOT EXISTS Account_Types (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(64) NOT NULL,
                    description TEXT NOT NULL
                )");

                $conn->exec("CREATE TABLE IF NOT EXISTS Transactions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    account_id INT NOT NULL,
                    type enum('deposit', 'withdrawal', 'transfer', 'fee', 'interest') NOT NULL,
                    amount DECIMAL(15, 2) NOT NULL,
                    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    description TEXT,
                    related_account_id INT NOT NULL
                )");

                require_once __DIR__ . '/../Infrastructure/seeder.php';
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
?>