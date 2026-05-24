<?php 
    require_once __DIR__ . '/../../shared/helpers.php';
    csrf_verify();
    
    $messageToUser = "";
    if(isset($_POST['type'])) { 
        if($_POST['type'] === 'Register') {
            
            require "/../../Shared/Infrastructure/database.php";
            $dbFactory = new DatabaseFactory();
            $conn = $dbFactory->createDatabaseConnection();
            
            $stmt = $conn->prepare("SELECT id, kortnummer, PIN-kod FROM Accounts WHERE kortnummer = ?");
            $stmt->execute([$_POST['kortnummer']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user) {
                $messageToUser = "Kortnummer finss redan.";
            } else {
                $stmt = $conn->prepare("INSERT INTO Accounts (kortnummer, PIN-kod) VALUES (?, ?)");
                $stmt->execute([
                    password_hash($_POST['kortnummer'], PASSWORD_DEFAULT),
                    password_hash($_POST['PIN-kod'], PASSWORD_DEFAULT)
                ]);
                
                header("Location: Login.php");
                exit();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register a new account</h1>
    <form method="post">
        <?php csrf_field(); ?>
        <label for="name">Full name: *</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email: *</label>
        <input type="email" id="email" name="email" required>

        <label for="dateofbirth">Date of Birth: *</label>
        <input type="date" id="dateofbirth" name="dateofbirth" required>

        <label for="phone">Phone Number: *</label>
        <input type="text" id="phone" name="phone" required>

        <label for="kortnummer">Kortnummer: *</label>
        <input type="text" id="kortnummer" name="kortnummer" required>

        <label for="PIN-kod">PIN-kod: *</label>
        <input type="password" id="PIN-kod" name="PIN-kod" required>

        <label for="account_type">Account Type: *</label>
        <select id="account_type" name="account_type" required>
            <option value="savings">Savings Account</option>
            <option value="checking">Checking Account</option>
        </select>

        <label for="currency">Currency: *</label>
        <select id="currency" name="currency" required>
            <option value="SEK">Swedish Krona</option>
            <option value="USD">US Dollar</option>
            <option value="EUR">Euro</option>
        </select>

        <button type="submit" value="Register">Register</button>
    </form>

</body>
</html>