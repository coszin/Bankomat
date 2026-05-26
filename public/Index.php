<?php 
    require_once __DIR__ . "/../Shared/Infrastructure/database.php";
    $dbFactory = new DatabaseFactory();
    $dbFactory->seedDatabase();
    
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
        <img src="/Bankomat/Shared/Img/BCO.01b88635-080a-4abb-a9cb-a00ff113fe58.png" alt="Söder logo" class="logo">
        </div>
    </header>
    <main>
        <h1>Välkomen till Bank Söder</h1>
        <div class="container">
            <form class="form" method="post"> 
                <button type="button" id="login" name="login" value="Logga in" onclick="window.location.href='../Features/Kund/Presentation/Login/Login.php'">Kund</button>

                <button type="button" id="admin" name="admin" value="Admin" onclick="window.location.href='../Features/Admin/Login/AdminLogin.php'">Admin</button>
            </form>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>Copyright © 2026 Bank Söder. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>