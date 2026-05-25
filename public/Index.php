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
        <h1>Bank Söder</h1>
        <div class="container">
            <form class="form" method="post"> 
                <button type="button" id="login" name="login" value="Logga in" onclick="window.location.href='/../Bankomat/Features/Authentication/Login.php'">Kund</button>

                <button type="button" id="admin" name="admin" value="Admin" onclick="window.location.href='/../Bankomat/Features/Authentication/AdminLogin.php'">Admin</button>
            </form>
        </div>
    </header>
    <main>
        <h2>Produkter</h2>
    </main>
    <footer>

    </footer>
</body>
</html>