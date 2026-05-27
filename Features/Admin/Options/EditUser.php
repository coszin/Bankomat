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
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("
        UPDATE users 
        SET firstname = ?, lastname = ?, email = ?, phone = ?, userrole = ?
        WHERE id = ?
    ");
    $stmt->execute([$firstname, $lastname, $email, $phone, $role, $id]);

    $message = "Användaren har uppdaterats!";
}

$users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redigera användare</title>
</head>
<body>

<h1>Redigera användare</h1>

<?php if ($message): ?>
    <p style="color: green;"><?= $message ?></p>
<?php endif; ?>

<form method="post">
    <?= csrf_field() ?>

    <label>Användare:</label>
    <select name="id">
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>">
                <?= $u['firstname'] ?> <?= $u['lastname'] ?> (<?= $u['email'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Förnamn:</label>
    <input type="text" name="firstname" required>
    <br><br>

    <label>Efternamn:</label>
    <input type="text" name="lastname" required>
    <br><br>

    <label>Email:</label>
    <input type="email" name="email" required>
    <br><br>

    <label>Telefon:</label>
    <input type="text" name="phone" required>
    <br><br>

    <label>Roll:</label>
    <select name="role">
        <option value="customer">Kund</option>
        <option value="admin">Admin</option>
    </select>
    <br><br>

    <button type="submit">Spara ändringar</button>
</form>

<a href="\Bankomat\Features\Admin\Dashboard\AdminDashboard.php">⬅ Tillbaka</a>
</body>
</html>
