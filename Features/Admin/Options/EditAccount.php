<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../Shared/helpers.php';
require_once __DIR__ . '/../Options/DatabaseConnection.php';

$factory = new DatabaseConnection();
$conn = $factory->getConnection();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $id = $_POST['id'];
    $balance = $_POST['balance'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE accounts 
        SET balance = ?, status = ?
        WHERE id = ?
    ");
    $stmt->execute([$balance, $status, $id]);

    $message = "Kontot har uppdaterats!";
}

$accounts = $conn->query("SELECT * FROM accounts")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redigera konto</title>
</head>
<body>

<h1>Redigera konto</h1>

<?php if ($message): ?>
    <p style="color: green;"><?= $message ?></p>
<?php endif; ?>

<form method="post">
    <?= csrf_field() ?>

    <label>Konto:</label>
    <select name="id">
        <?php foreach ($accounts as $acc): ?>
            <option value="<?= $acc['id'] ?>">
                <?= $acc['kortnummer'] ?> (Saldo: <?= $acc['balance'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Nytt saldo:</label>
    <input type="number" step="0.01" name="balance" required>
    <br><br>

    <label>Status:</label>
    <select name="status">
        <option value="active">Aktiv</option>
        <option value="inactive">Inaktiv</option>
        <option value="closed">Stängd</option>
    </select>
    <br><br>

    <button type="submit">Spara ändringar</button>
</form>

<a href="\Bankomat\Features\Admin\Dashboard\AdminDashboard.php">⬅ Tillbaka</a>
</body>
</html>
