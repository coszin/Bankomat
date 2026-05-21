<?php 
    session_start();
    if(isset($_SESSION['user_id'])) {
        echo "<br>" . htmlspecialchars($_SESSION['user_id']);
        echo "<br>" . htmlspecialchars($_SESSION['user_role']);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Söder</title>
</head>
<body>
    <header>
        <h1>Bank Söder</h1>
        <a href="login.php">Login</a>
    </header>
    <main>
        <h2>Produkter</h2>
    </main>
    <footer>

    </footer>
</body>
</html>