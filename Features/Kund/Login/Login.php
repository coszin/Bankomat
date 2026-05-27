<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    require_once __DIR__ . '/../../../Shared/helpers.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_verify();
    }

    $messageToUser = "";
    if(isset($_POST['type'])) { 
        if(($_POST['type'] === 'login')) {
            
            require __DIR__ . "/../../../Shared/Infrastructure/database.php";
            $dbFactory = new DatabaseFactory();
            $conn = $dbFactory->createDatabaseConnection();
            
            require __DIR__ . "/LoginValidator.php";
            $validator = new LoginValidation();
            $errors = $validator->validate($_POST);

            if (!empty($errors)) {
                $messageToUser = implode("<br>", $errors);
            } else {
                // Fetch user by card number
                $stmt = $conn->prepare("
                    SELECT id, kortnummer, pinkod, login_attempts, locked_until, user_id
                    FROM accounts
                    WHERE kortnummer = ?
                ");
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

                    require_once __DIR__ . "/../../../Shared/Repository/KundRepository.php";
                    $repo = new KundRepository($conn);

                    // 1. Get the account
                    $account = $repo->getAccountsByKortnumer($_POST['kortnummer']);

                    // 2. Get the user who owns the account
                    $user = $repo->getUserById($account['user_id']);

                    // 3. Save correct user data to session
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'firstname' => $user['firstname'],
                        'userrole' => $user['userrole']
                    ];

                    session_regenerate_id(true);
                    header("Location: ../Dashboard/KundDashboard.php");
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
            header('Location: ../../Registration/Register.php');
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
        <link rel="stylesheet" href="/Bankomat/public/Index.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <header>
            <div class="container">
                <h1>Bank Söder</h1>
            </div>
            <div class="container">
            <img src="../../../Shared/Img/BankSöderLogoTop.png" alt="Söder logo" class="logo">
            </div>
        </header>
        <main>
            <h1>Logga in</h1>
            <div class="container">
                <p class="text"><?php echo $messageToUser; ?></p>
                <form class="form" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="type" value="login">
                    
                    <label for="Kortnummer">Kortnummer:</label>
                    <input type="number" inputmode="numeric" pattern="[0-9]*" placeholder="Enbart number" name="kortnummer" value="<?php echo htmlspecialchars($_POST['kortnummer'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <label for="PIN-kod">PIN-kod:</label>
                    <input type="number" inputmode="numeric" pattern="[0-9]*" placeholder="Enbart number"name="pinkod" value="<?php echo htmlspecialchars($_POST['pinkod'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <button type="submit" name="type" value="login">Logga in</button>
                    <button type="submit" name="type" value="Register new account">Skapa nytt konto</button>
                </form>
                <a href="/Bankomat/Public/Index.php">⬅ Tillbaka</a>
            </div>
        </main>
        <footer>
            <div class="container">
                <p>&copy; 2026 Bank Söder. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>