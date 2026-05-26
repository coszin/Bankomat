<?php 
    require_once __DIR__ . '/../../../Shared/helpers.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_verify();
    }
    
    $messageToUser = "";
    if(isset($_POST['type'])) { 

        require __DIR__ . "/../../../Shared/Infrastructure/database.php";
        $dbFactory = new DatabaseFactory();
        $conn = $dbFactory->createDatabaseConnection();
    
        // Fetch user by card number and join account admin role
        $stmt = $conn->prepare("SELECT accounts.id, accounts.kortnummer, accounts.pinkod, accounts.user_id, users.userrole FROM accounts JOIN users ON accounts.user_id = users.id WHERE accounts.kortnummer = ?");
        $stmt->execute([$_POST['kortnummer']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(($_POST['type'] === 'login')) {
            
            require __DIR__ . "/AdminLoginValidator.php";
            $validator = new AdminLoginValidation();
            $errors = $validator->validate($_POST);
            
            if (!empty($errors)) {
                $messageToUser = implode("<br>", $errors);
            } else {
                    
                if ($user && $user['userrole'] === 'admin') {
                    // Check if account is locked
                    if ($user && strtotime($user['locked_until']) > time()) {
                        $messageToUser = "Account locked. Try again later.";
                    } 
                    // Check login attempt count
                    else if ($user && $user['login_attempts'] >= 3) {
                        $stmt = $conn->prepare("UPDATE accounts SET locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE id = ?");
                        $stmt->execute([$user['id']]);
                        $messageToUser = "Account locked after 3 failed attempts.";
                    }
                    // Check password
                    else if ($user && password_verify($_POST['pinkod'], $user['pinkod'])) {
                        $stmt = $conn->prepare("UPDATE accounts SET login_attempts = 0, locked_until = NULL WHERE id = ?");
                        $stmt->execute([$user['id']]);
                        $_SESSION['user_id'] = $user['id'];
                        header("Location: ../Dashboard/AdminDashboard.php");
                        exit();
                    }
                    // Failed login
                    else {
                        $stmt = $conn->prepare("UPDATE accounts SET login_attempts = login_attempts + 1 WHERE id = ?");
                        $stmt->execute([$user['id']]);
                        $messageToUser = "Kortnummer eller PIN-kod är felaktigt.";
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logga in, Admin.</title>
        <link rel="stylesheet" href="/Bankomat/public/Index.css?v=<?php echo time(); ?>">
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
            <h1>Logga in, Admin.</h1>
            <div class="container">
                <form class="form" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="type" value="login">
                    
                    <label for="kortnummer">kortnummer:</label>
                    <input type="text" pattern="[0-9]*" placeholder="Enbart number" id="kortnummer" name="kortnummer">
                    
                    <label for="PIN-kod">PIN-kod:</label>
                    <input type="text" pattern="[0-9]*" placeholder="Enbart number" id="PIN-kod" name="pinkod">
                    
                    <button type="submit" name="type" value="login">Logga in</button>
                </form>
            </div>
            <p class="text"><?php echo $messageToUser; ?></p>
        </main>
        <footer>
            <div class="container">
                <p>&copy; 2026 Bank Söder. All rights reserved.</p>
            </div>
        </footer>
    </body>
    </html>