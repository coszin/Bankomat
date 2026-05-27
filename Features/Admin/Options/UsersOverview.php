<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../Shared/helpers.php';
require_once __DIR__ . '/../Options/DatabaseConnection.php';

$factory = new DatabaseConnection();
$conn = $factory->getConnection();

$stmt = $conn->query("SELECT id, firstname, lastname, email, phone, userrole FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alla användare</title>
</head>
<body>

<h1>Alla användare</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Förnamn</th>
        <th>Efternamn</th>
        <th>Email</th>
        <th>Telefon</th>
        <th>Roll</th>
    </tr>

    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['firstname']) ?></td>
            <td><?= htmlspecialchars($u['lastname']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone']) ?></td>
            <td><?= htmlspecialchars($u['userrole']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="\Bankomat\Features\Admin\Dashboard\AdminDashboard.php">⬅ Tillbaka</a>
</body>
</html>
