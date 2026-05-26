<?php 
    require_once __DIR__ . '/../../shared/helpers.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_verify();
    }
    
    $messageToUser = "";
    if(isset($_POST['type']) && $_POST['type'] === 'Register') { 
        // if($_POST['type'] === 'Register') {
            
            require __DIR__ . "/../../Shared/Infrastructure/database.php";
            $dbFactory = new DatabaseFactory();
            $conn = $dbFactory->createDatabaseConnection();
            
            $stmt = $conn->prepare("SELECT id, kortnummer, pinkod FROM Accounts WHERE kortnummer = ?");
            $stmt->execute([$_POST['kortnummer']]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $conn->prepare("SELECT firstname, lastname, phone, email, dateofbirth FROM Users WHERE email = ?");
            $stmt->execute([$_POST['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($account) {
                $messageToUser = "Kortnummer finss redan.";
            }else if($user) {
                $messageToUser = "Konto med dessa uppgifter finns redan.";
            } else {
                $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, phone, email, dateofbirth) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['firstname'],
                    $_POST['lastname'],
                    $_POST['phone'],
                    $_POST['email'],
                    $_POST['dateofbirth']
                ]);

                $stmt = $conn->prepare("INSERT INTO Accounts (kortnummer, pinkod, user_id, account_type, currency) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['kortnummer'],
                    password_hash($_POST['pinkod'], PASSWORD_DEFAULT),
                    $_SESSION['user_id'],
                    $_POST['account_type'],
                    $_POST['currency']
                ]);
                
                header("Location: /Bankomat/Features/Kund/Presentation/Login/Login.php");
                exit;

            }
        // }
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
    <header>
        <div class="container">
            <h1>Bank Söder</h1>
        </div>
        <div class="container">
        <img src="/Bankomat/Shared/Img/BCO.01b88635-080a-4abb-a9cb-a00ff113fe58.png" alt="Söder logo" class="logo">
        </div>
    </header>
    <main>
        <h1>Registrera nytt konto</h1>
        <div class="container">
            <form class="form" method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" value="Register">

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
                
                <label for="pinkod">PIN-kod: *</label>
                <input type="password" id="pinkod" name="pinkod" required>
                
                <label for="account_type">Kontotyp: *</label>
                <select id="account_type" name="account_type" required>
                    <option value=1>Sparkonto</option>
                    <option value=2>Transaktionskonto</option>
                </select>
                
                <label for="currency">Valuta: *</label>
                <select id="currency" name="currency" required>
                    <option value="SEK">Svenska kronor</option>
                    <option value="USD">US-dollar</option>
                    <option value="EUR">Euro</option>
                </select>
                
                <button type="submit" name="type" value="Register">Registrera</button>
            </form>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2026 Bank Söder. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>