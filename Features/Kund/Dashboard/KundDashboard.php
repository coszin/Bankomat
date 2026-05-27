<?php
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    require_once __DIR__ . '/../../../Shared/helpers.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_verify();
    }

switch ($_POST['type'] ?? '') {
    case 'Withdraw':
        header("Location: /Bankomat/Features/Kund/Options/Withdraw.php");
        exit();

    case 'deposit':
        header("Location: /Bankomat/Features/Kund/Options/Deposit.php");
        exit();

        case 'transfer':
            header("Location: /Bankomat/Features/Kund/Options/Transfer.php");
        exit();

    case 'logout':
        require_once __DIR__ . "/../Options/Logout.php";
        $logout = new Logout();
        $logout->execute();
        exit();
    case 'Avbryt':
        // Stay on dashboard
        break;
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
            <img src="../../../Shared/Img/BankSöderLogoTop.png" alt="Söder logo" class="logo">
        </div>
    </header>
    <main>
        <div class="split_container">

            <div class="left">

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
                <div>
                    <?php
                        require_once __DIR__ . "/../Options/overview.php";
                        $overview = new Overview();
                        $overview->execute();  
                    ?>
                </div>
            </div>

        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2026 Bank Söder. All rights reserved.</p>

            <?php
                if (isset($_SESSION['user']) && $_SESSION['user']['userrole'] === 'admin') {
                    echo "<a style='color:#D4A017;' href='/Bankomat/Features/Admin/Dashboard/AdminDashboard.php'>Admin</a>";
                }
            ?>
        </div>
    </footer>
</body>
</html>