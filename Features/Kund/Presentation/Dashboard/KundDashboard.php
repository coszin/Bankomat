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
    <link rel="stylesheet" href="KundDashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <div class="container">
            <h1>Bank Söder</h1>
        </div>
        <div class="container">
            <img src="../../../../Shared/Img/BankSöderLogoTop.png" alt="Söder logo" class="logo">
        </div>
    </header>
    <main>
        <div class="split_container">
            <div class="left">
                <?php
                    if(isset($_POST['type']) && $_POST['type'] === 'Withdraw') {
                        header("Location: Options/Withdraw.php");
                        exit();
                    } else if(isset($_POST['type']) && $_POST['type'] === 'deposit') {
                        
                    } else if(isset($_POST['type']) && $_POST['type'] === 'transfer') {
                    
                    }
                
                ?>
            </div>
            <div class="container">
                <h1>Kund Dashboard</h1>
                <form class="form" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="type" value="logout" value="true">

                    <button type="submit" name="type" value="Withdraw">Ta ut pengar</button>

                    <button type="submit" name="type" value="deposit">Sätt in pengar</button>

                    <button type="submit" name="type" value="transfer">Överför pengar</button>

                    <button type="submit" name="type" value="logout">Logga ut</button>
                </form>
            </div>
            <div class="right">

            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2026 Bank Söder. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>