<?php
    require_once __DIR__ . '/../../../../Shared/helpers.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_verify();
    }

    switch ($_POST['type'] ?? '') {
        case 'Withdraw':
            header("Location: Options/Withdraw.php");
            exit();
        case 'deposit':
            header("Location: Options/Deposit.php");
            exit();
        case 'transfer':
            header("Location: Options/Transfer.php");
            exit();
        case 'overview':
            header("Location: Options/Overview.php");
            exit();
        case 'new_card':
            header("Location: Options/NewCard.php");
            exit();
        case 'logout':
            require __DIR__ . "/../Options/Logout.php";
            $logout = new Logout();
            $logout->execute();
            exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        <h1>Kund Dashboard</h1>
        <div class="container">
            <form class="form" method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" value="logout" value="true">

                <button type="submit" name="type" value="Withdraw">Ta ut pengar</button>

                <button type="submit" name="type" value="deposit">Sätt in pengar</button>

                <button type="submit" name="type" value="transfer">Överför pengar</button>

                <button type="submit" name="type" value="overview">Överblick av alla kort</button>

                <button type="submit" name="type" value="new_card">Nytt kort</button>

                <button type="submit" name="type" value="logout">Logga ut</button>
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