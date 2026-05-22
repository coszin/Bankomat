<?php 
    require_once __DIR__ . '\..\..\helpers.php';
    csrf_verify();
    
    $messageToUser = "";
    if(isset($_POST['type'])) { 
        if($_POST['type'] === 'Register') {
            
            require "/Bankomat/Shared/Infrastructure/database.php";
            $dbFactory = new DatabaseFactory();
            $conn = $dbFactory->createDatabaseConnection();
            
            $stmt = $conn->prepare("SELECT id, kortnummer, PIN-kod, userrole FROM users WHERE kortnummer = ?");
            $stmt->execute([$_POST['kortnummer']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user) {
                $messageToUser = "Kortnummer already exists.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (kortnummer, PIN-kod, userrole) VALUES (?, ?, ?)");
                $stmt->execute([
                    password_hash($_POST['kortnummer'], PASSWORD_DEFAULT),
                    password_hash($_POST['PIN-kod'], PASSWORD_DEFAULT),
                    'customer'
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

        <label for="national_id">National ID: *</label>
        <input type="text" id="national_id" name="national_id" required>

        <button type="submit" value="Register">Register</button>
    </form>

</body>
</html>