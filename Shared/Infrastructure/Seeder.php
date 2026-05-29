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
        'Karl',
        'Gösta',
        '1234567890',
        'Karl.Gösta@example.com',
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

$stmt = $conn->prepare("INSERT INTO accounts (kortnummer, pinkod, user_id, account_type, balance, currency, status) VALUES (?, ?, ?, ?, ?, ?, ?)");

// User 1 → 2 accounts
$stmt->execute(['2000000120000001', password_hash('1234', PASSWORD_DEFAULT), 1, 1, 1500.00, 'SEK', 'active']);
$stmt->execute(['2000000220000002', password_hash('5678', PASSWORD_DEFAULT), 1, 2, 8200.50, 'SEK', 'active']);

// User 2 → 1 account
$stmt->execute(['2000000320000003', password_hash('1111', PASSWORD_DEFAULT), 2, 1, 500.00, 'SEK', 'active']);

// User 3 → 3 accounts
$stmt->execute(['2000000420000004', password_hash('2222', PASSWORD_DEFAULT), 3, 2, 12000.00, 'SEK', 'active']);
$stmt->execute(['2000000520000005', password_hash('3333', PASSWORD_DEFAULT), 3, 1, 75.25, 'SEK', 'active']);
$stmt->execute(['2000000620000006', password_hash('4444', PASSWORD_DEFAULT), 3, 2, 999.99, 'SEK', 'active']);

// User 4 → 1 account
$stmt->execute(['2000000720000007', password_hash('5555', PASSWORD_DEFAULT), 4, 1, 450.00, 'SEK', 'active']);

// User 5 → 2 accounts
$stmt->execute(['2000000820000008', password_hash('6666', PASSWORD_DEFAULT), 5, 2, 30000.00, 'SEK', 'active']);
$stmt->execute(['2000000920000009', password_hash('7777', PASSWORD_DEFAULT), 5, 1, 120.00, 'SEK', 'active']);

// User 6 → 1 account
$stmt->execute(['2000001020000010', password_hash('8888', PASSWORD_DEFAULT), 6, 2, 780.40, 'SEK', 'active']);

// User 7 → 3 accounts
$stmt->execute(['2000001120000011', password_hash('9012', PASSWORD_DEFAULT), 7, 1, 2500.00, 'SEK', 'active']);
$stmt->execute(['2000001220000012', password_hash('2109', PASSWORD_DEFAULT), 7, 2, 50.00, 'SEK', 'active']);
$stmt->execute(['2000001320000013', password_hash('1478', PASSWORD_DEFAULT), 7, 1, 9999.00, 'SEK', 'active']);

// User 8 → 1 account
$stmt->execute(['2000001420000014', password_hash('2589', PASSWORD_DEFAULT), 8, 2, 340.00, 'SEK', 'active']);

// User 9 → 2 accounts
$stmt->execute(['2000001520000015', password_hash('3691', PASSWORD_DEFAULT), 9, 1, 670.00, 'SEK', 'active']);
$stmt->execute(['2000001620000016', password_hash('1597', PASSWORD_DEFAULT), 9, 2, 120.75, 'SEK', 'active']);

// User 10 → 1 account
$stmt->execute(['2000001720000017', password_hash('7531', PASSWORD_DEFAULT), 10, 1, 890.00, 'SEK', 'active']);

// User 11 → 1 account
$stmt->execute(['2000001820000018', password_hash('8524', PASSWORD_DEFAULT), 11, 2, 4500.00, 'SEK', 'active']);

// User 12 → 1 account
$stmt->execute(['2000001920000019', password_hash('9513', PASSWORD_DEFAULT), 12, 1, 60.00, 'SEK', 'active']);

// User 13 → 1 account
$stmt->execute(['2000002020000020', password_hash('2046', PASSWORD_DEFAULT), 13, 2, 15000.00, 'SEK', 'active']);



$stmt = $conn->prepare("INSERT INTO users (firstname, lastname, phone, email, dateofbirth, userrole) VALUES (?, ?, ?, ?, ?, ?)");

$stmt->execute(['Oscar', 'Svahn', '0701112233', 'oscar.svahn@example.com', '1999-04-12', 'customer']);
$stmt->execute(['Emma', 'Lindgren', '0702223344', 'emma.lindgren@example.com', '1995-02-18', 'customer']);
$stmt->execute(['Liam', 'Johansson', '0703334455', 'liam.johansson@example.com', '2001-11-03', 'customer']);
$stmt->execute(['Maja', 'Karlsson', '0704445566', 'maja.karlsson@example.com', '1998-07-29', 'customer']);
$stmt->execute(['Noah', 'Svensson', '0705556677', 'noah.svensson@example.com', '1997-09-14', 'customer']);
$stmt->execute(['Ella', 'Bergström', '0706667788', 'ella.bergstrom@example.com', '2000-01-22', 'customer']);
$stmt->execute(['William', 'Ekström', '0707778899', 'william.ekstrom@example.com', '1996-05-09', 'customer']);
$stmt->execute(['Alma', 'Holm', '0708889900', 'alma.holm@example.com', '2002-03-17', 'customer']);
$stmt->execute(['Lucas', 'Nyberg', '0709990011', 'lucas.nyberg@example.com', '1994-10-30', 'customer']);
$stmt->execute(['Astrid', 'Viklund', '0711112233', 'astrid.viklund@example.com', '1993-12-05', 'customer']);
$stmt->execute(['Elias', 'Håkansson', '0712223344', 'elias.hakansson@example.com', '1999-08-11', 'customer']);
$stmt->execute(['Freja', 'Dahl', '0713334455', 'freja.dahl@example.com', '1998-06-26', 'customer']);
$stmt->execute(['Arvid', 'Sandberg', '0714445566', 'arvid.sandberg@example.com', '1997-01-15', 'admin']);

?>