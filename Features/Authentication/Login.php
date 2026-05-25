<?php 
    require_once __DIR__ . '/../../Shared/helpers.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_verify();
    }

    $messageToUser = "";
    if(isset($_POST['type'])) { 
        if(($_POST['type'] === 'login')) {
            
            require __DIR__ . "/../../Shared/Infrastructure/database.php";
            $dbFactory = new DatabaseFactory();
            $conn = $dbFactory->createDatabaseConnection();
            
            require __DIR__ . "/LoginValidator.php";
            $validator = new LoginValidation();
            $errors = $validator->validate($_POST);

            if (!empty($errors)) {
                $messageToUser = implode("<br>", $errors);
            } else {
                // Fetch user by card number
                $stmt = $conn->prepare("SELECT id, kortnummer, pinkod FROM accounts WHERE kortnummer = ?");
                $stmt->execute([$_POST['kortnummer']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
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
                    header("Location: ../Features/Dashboard.php");
                    exit();
                }
                // Failed login
                else {
                    $stmt = $conn->prepare("UPDATE accounts SET login_attempts = login_attempts + 1 WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    $messageToUser = "Kortnummer eller PIN-kod är felaktigt.";
                }
            }
        } else if($_POST['type'] === 'Register new account') {
            header('Location: ../Registration/Register.php');
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logga in</title>
        <link rel="stylesheet" href="Login.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <h1>Logga in</h1>
        <p class="text"><?php echo $messageToUser; ?></p>
        <div class="container">
            <form class="form" method="post">
                <?php echo csrf_field(); ?>
                <label for="Kortnummer">Kortnummer:</label>
                <input type="number" inputmode="numeric" pattern="[0-9]*" placeholder="Enbart number" id="Kortnummer" name="kortnummer">

                <label for="PIN-kod">PIN-kod:</label>
                <input type="number" inputmode="numeric" pattern="[0-9]*" placeholder="Enbart number" id="PIN-kod" name="pinkod">
                
                <button type="submit" name="type" value="login">Logga in</button>
                <button type="submit" name="type" value="Register new account">Skapa nytt konto</button>
            </form>
        </div>
    </body>
    </html>