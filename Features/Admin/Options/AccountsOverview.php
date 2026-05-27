<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../Shared/helpers.php';
require_once __DIR__ . '/../Options/DatabaseConnection.php';
require_once __DIR__ . '/../../../Shared/Repository/KundRepository.php';

$factory = new DatabaseConnection();
$conn = $factory->getConnection();
$repo = new KundRepository($conn);

$accounts = $repo->getAllAccountNumbersWithNames();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alla konton</title>
</head>
<body>
<h1>Alla konton</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>Kortnummer</th>
        <th>Förnamn</th>
        <th>Efternamn</th>
    </tr>

    <?php foreach ($accounts as $acc): ?>
        <tr>
            <td><?= htmlspecialchars($acc['kortnummer']) ?></td>
            <td><?= htmlspecialchars($acc['firstname']) ?></td>
            <td><?= htmlspecialchars($acc['lastname']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="\Bankomat\Features\Admin\Dashboard\AdminDashboard.php">⬅ Tillbaka</a>
</body>
</html>
