<?php 
    require_once __DIR__ . '/../../shared/helpers.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_verify();
    }
    
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
                $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, phone, email, dateofbirth) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['firstname'],
                    $_POST['lastname'],
                    $_POST['phone'],
                    $_POST['email'],
                    $_POST['dateofbirth']
                ]);

                $stmt = $conn->prepare("INSERT INTO Accounts (kortnummer, PIN-kod, user_id, account_type, currency) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['kortnummer'],
                    password_hash($_POST['PIN-kod'], PASSWORD_DEFAULT),
                    $_SESSION['user_id'],
                    $_POST['account_type'],
                    $_POST['currency']
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
    <title>Registrera</title>
    <link rel="stylesheet" href="Register.css?v=<?php echo time(); ?>">
</head>
<body>
    <h1>Registrera nytt konto</h1>
    <div class="container">
        <form class="form" method="post">
            <?php echo csrf_field(); ?>
            <label for="firstname">Förnamn: *</label>
            <input type="text" id="firstname" name="firstname" required>
            
            <label for="lastname">Efternamn: *</label>
            <input type="text" id="lastname" name="lastname" required>
            
            <label for="email">E-post: *</label>
            <input type="email" id="email" name="email" required>
            
            <label for="dateofbirth">Födelsedatum: *</label>
            <input type="date" id="dateofbirth" name="dateofbirth" required>
            
            <label for="phone">Telefonnummer: *</label>
            <input type="text" id="phone" name="phone" required>
            
            <label for="kortnummer">Kortnummer: *</label>
            <input type="text" id="kortnummer" name="kortnummer" required>
            
            <label for="PIN-kod">PIN-kod: *</label>
            <input type="password" id="PIN-kod" name="PIN-kod" required>
            
            <label for="account_type">Kontotyp: *</label>
            <select id="account_type" name="account_type" required>
                <option value="savings">Sparkonto</option>
                <option value="checking">Transaktionskonto</option>
            </select>
            
            <label for="currency">Valuta: *</label>
            <select id="currency" name="currency" required>
                <option value="SEK">Svenska kronor</option>
                <option value="USD">US-dollar</option>
                <option value="EUR">Euro</option>
            </select>
            
            <button type="submit" value="Register">Registrera</button>
        </form>
    </div>
        
</body>
</html>