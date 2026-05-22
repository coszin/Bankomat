<?php 
    require_once __DIR__ . '\..\..\helpers.php';
    
    $messageToUser = "";
    if(isset($_POST['type'])) { 
        if($_POST['type'] === 'login') {
            
            require "/Bankomat/Shared/Infrastructure/database.php";
            $dbFactory = new DatabaseFactory();
            $conn = $dbFactory->createDatabaseConnection();
            
            $stmt = $conn->prepare("SELECT id, kortnummer, PIN-kod, userrole FROM users WHERE kortnummer = ?");
            $stmt->execute([$_POST['kortnummer']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($user && password_verify($_POST['kortnummer'], $user['kortnummer']) && password_verify($_POST['PIN-kod'], $user['PIN-kod'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['userrole'];
                header("Location: index.php");
                exit();
            } 
            else {
                $messageToUser = "Kortnummer eller PIN-kod är felaktigt.";
            } 
            } else if($_POST['type'] === 'Cant login') {
                header('Location: CantLogin.php');
                exit();
            } else if($_POST['type'] === 'Register new account') {
                header('Location: /Bankomat/Features/Registration/Register.php');
                exit();
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
        <h2>Login</h2>
        <?php echo $messageToUser; ?>
        <form method="post">
            <?php csrf_field(); ?>
            <label for="Kortnummer">Kortnummer:</label>
            <input type="text" id="Kortnummer" name="kortnummer">
            
            <label for="PIN-kod">PIN-kod:</label>
            <input type="password" id="PIN-kod" name="PIN-kod">
            
            <button type="submit" name="type" value="login">Login</button>
            <button type="submit" name="type" value="Cant login">Cant login</button>
            <button type="submit" name="type" value="Register new account">Register new account</button>
        </form>
    </body>
    </html>