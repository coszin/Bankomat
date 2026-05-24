<?php
    // require_once __DIR__ . '/../Infrastructure/database.php';
    $dbFactory = new DatabaseFactory();
    $conn = $dbFactory->createDatabaseConnection();

    $stmt = $conn->prepare("INSERT INTO account_types (name, description, stock, price) VALUES (?, ?, ?, ?)");
    $stmt->execute([ 
        'Sparkonto', 
        'Ett sparkonto är ett bankkonto där du kan spara pengar och få ränta på ditt saldo. Det är ett säkert sätt att spara pengar och kan vara ett bra alternativ för långsiktigt sparande.',
        100,
        0
    ]);
    $stmt->execute([ 
        'Transaktionskonto', 
        'Ett transaktionskonto är ett bankkonto där du kan göra transaktioner som insättningar, uttag och överföringar. Det är ett vanligt konto för dagliga finansiella aktiviteter.',
        100,
        0
    ]);

    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, phone, email, dateofbirth, userrole) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        'Oscar',
        'Svahn',
        '1234567890',
        'oscar.svahn@example.com',
        '1990-01-01',
        'Admin'
    ]);
?>