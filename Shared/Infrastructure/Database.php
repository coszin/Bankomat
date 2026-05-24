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

                $conn->exec("CREATE TABLE IF NOT EXISTS Customers (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    firstname VARCHAR(64) NOT NULL,
                    lastname VARCHAR(64) NOT NULL,
                    phone VARCHAR(64) NOT NULL,
                    email VARCHAR(128) NOT NULL,
                    dateofbirth DATE NOT NULL,
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

                // $conn->exec("CREATE TABLE IF NOT EXISTS Users (
                //     id INT AUTO_INCREMENT PRIMARY KEY,
                //     username VARCHAR(64) NOT NULL,
                //     password VARCHAR(255) NOT NULL,
                //     userrole enum('customer', 'employee', 'admin') NOT NULL
                // )");      

                // $conn->exec("CREATE TABLE IF NOT EXISTS Cards (
                //     id INT AUTO_INCREMENT PRIMARY KEY,
                //     account_id INT NOT NULL,
                //     card_number VARCHAR(16) NOT NULL,
                //     expiration_date DATE NOT NULL,
                //     card_type enum('debit', 'credit') NOT NULL,
                //     status enum('active', 'inactive', 'blocked') NOT NULL
                // )");

                // $conn->exec("CREATE TABLE IF NOT EXISTS Loans (
                //     id INT AUTO_INCREMENT PRIMARY KEY,
                //     customer_id INT NOT NULL,
                //     amount DECIMAL(15, 2) NOT NULL,
                //     interest_rate DECIMAL(5, 2) NOT NULL,
                //     loan_type enum('personal', 'mortgage', 'auto') NOT NULL,
                //     start_date DATE NOT NULL,
                //     end_date DATE NOT NULL,
                //     remaining_balance DECIMAL(15, 2) NOT NULL
                // )");                

                // $conn->exec("CREATE TABLE IF NOT EXISTS Loan_Payments (
                //     id INT AUTO_INCREMENT PRIMARY KEY,
                //     loan_id INT NOT NULL,
                //     amount DECIMAL(15, 2) NOT NULL,
                //     payment_date DATE NOT NULL
                //     )");      

                // $conn->exec("CREATE TABLE IF NOT EXISTS Audit_Log (
                //     id INT AUTO_INCREMENT PRIMARY KEY,
                //     user_id INT NOT NULL,
                //     action VARCHAR(255) NOT NULL,
                //     timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                //     affected_table VARCHAR(64) NOT NULL,
                //     affected_record_id INT NOT NULL
                // )");      
                
                // $conn->exec("CREATE TABLE IF NOT EXISTS Authentication_Log (
                //     id INT AUTO_INCREMENT PRIMARY KEY,
                //     user_id INT NOT NULL,
                //     username_attempt VARCHAR(64) NOT NULL,
                //     timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                //     ip_address VARCHAR(45) NOT NULL,
                //     user_agent VARCHAR(255) NOT NULL,
                //     success BOOLEAN NOT NULL,
                //     failure_reason ENUM('invalid_username', 'invalid_password', 'account_locked', 'other') NULL
                // )");    

                require_once __DIR__ . '/../Infrastructure/seeder.php';
            }
            catch(PDOException $e) {
                echo "FEEEELL";
            }
        }
    }
?>