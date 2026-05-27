<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$msgToUser = "";
    if(isset($_POST['generate_db']) && $_POST['generate_db'] === 'generate_db') {
        require_once __DIR__ . "/../Shared/Infrastructure/database.php";
        $dbFactory = new DatabaseFactory();
        $dbFactory->seedDatabase();
        $msgToUser = "Databas genererad.";
    }
    require_once __DIR__ . '\..\Shared\helpers.php';
    csrf_token();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Söder</title>
    <link rel="stylesheet" href="Index.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <div class="container">
            <h1>Bank Söder</h1>
        </div>
        <div class="container">
            <img src="../Shared/Img/BankSöderLogoTop.png" alt="Söder logo" class="logo">
        </div>
    </header>
    <main>
        <h1>Välkomen till Bank Söder</h1>
        <div class="container">
            <form class="form" method="post"> 
                <button type="button" id="login" name="login" value="Logga in" onclick="window.location.href='../Features/Kund/Login/Login.php'">Logga in</button>

                <!-- <button type="button" id="admin" name="admin" value="Admin" onclick="window.location.href='../Features/Admin/Login/AdminLogin.php'">Admin</button> -->

                <button type="submit" id="generate_db" name="generate_db" value="generate_db">Generera Databas</button>
            </form>
            <h2 style="color: #D4A017;"><?php echo $msgToUser; ?></h2>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>Copyright © 2026 Bank Söder. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>