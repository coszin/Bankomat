<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../../../Shared/helpers.php';
    require_once __DIR__ . '/../Options/DatabaseConnection.php';

    $factory = new DatabaseConnection();
    $conn = $factory->getConnection();

    $sql = "
        SELECT 
            t.id,
            fa.kortnummer AS from_kortnummer,
            ta.kortnummer AS to_kortnummer,
            t.amount,
            t.type,
            t.created_at
        FROM transactions t
        LEFT JOIN accounts fa ON t.from_account_id = fa.id
        LEFT JOIN accounts ta ON t.to_account_id = ta.id
        ORDER BY t.created_at DESC
    ";

    $stmt = $conn->query($sql);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaktioner</title>
</head>
<body>

<h1>Alla transaktioner</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Från konto</th>
        <th>Till konto</th>
        <th>Belopp</th>
        <th>Typ</th>
        <th>Datum</th>
    </tr>

    <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= $t['from_kortnummer'] ?: '-' ?></td>
            <td><?= $t['to_kortnummer'] ?: '-' ?></td>
            <td><?= $t['amount'] ?></td>
            <td><?= ucfirst($t['type']) ?></td>
            <td><?= $t['created_at'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="\Bankomat\Features\Admin\Dashboard\AdminDashboard.php">⬅ Tillbaka</a>

</body>
</html>
