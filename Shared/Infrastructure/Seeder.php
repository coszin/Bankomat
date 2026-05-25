<?php
    // require_once __DIR__ . '/../Infrastructure/database.php';
    $dbFactory = new DatabaseFactory();
    $conn = $dbFactory->createDatabaseConnection();

    $stmt = $conn->prepare("INSERT IGNORE INTO account_types (name, description) VALUES (?, ?)");
    $stmt->execute([ 
        'Sparkonto', 
        'Ett sparkonto är ett bankkonto där du kan spara pengar och få ränta på ditt saldo. Det är ett säkert sätt att spara pengar och kan vara ett bra alternativ för långsiktigt sparande.',
    ]);
    $stmt->execute([ 
        'Transaktionskonto', 
        'Ett transaktionskonto är ett bankkonto där du kan göra transaktioner som insättningar, uttag och överföringar. Det är ett vanligt konto för dagliga finansiella aktiviteter.',
    ]);

    $stmt = $conn->prepare("INSERT IGNORE INTO users (firstname, lastname, phone, email, dateofbirth, userrole) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        'Oscar',
        'Svahn',
        '1234567890',
        'oscar.svahn@example.com',
        '1990-01-01',
        'Admin'
    ]);

    $stmt = $conn->prepare("INSERT IGNORE INTO accounts (kortnummer, pinkod, user_id, account_type, balance, currency, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        '1212121212121212',
        password_hash('1212', PASSWORD_DEFAULT),
        1,
        1,
        1000.00,
        'SEK',
        'active'
    ]);
?>