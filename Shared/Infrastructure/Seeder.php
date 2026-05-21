<?php
    // require_once __DIR__ . '/../Infrastructure/database.php';
    $dbFactory = new DatabaseFactory();
    $conn = $dbFactory->createDatabaseConnection();

    // $stmt = $conn->prepare("INSERT INTO products (name, description, stock, price) VALUES (?, ?, ?, ?)");
    // $stmt->execute(["Gurka", "Its green", 10, 19.99]);
    // $stmt->execute(["Körsbär", "Its cherry red", 5, 29.99]);
    // $stmt->execute(["Äpple", "Its red", 20, 9.99]);

?>