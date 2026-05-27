<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    require_once __DIR__ . '/../../../Shared/helpers.php';
    require_admin();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}

switch ($_POST['type'] ?? '') {

    case 'accounts_overview':
        header("Location: /Bankomat/Features/Admin/Options/AccountsOverview.php");
        exit();

    case 'users_overview':
        header("Location: /Bankomat/Features/Admin/Options/UsersOverview.php");
        exit();

    case 'edit_account':
        header("Location: /Bankomat/Features/Admin/Options/EditAccount.php");
        exit();

    case 'edit_user':
        header("Location: /Bankomat/Features/Admin/Options/EditUser.php");
        exit();

        case 'transactions_overview':
        header("Location: /Bankomat/Features/Admin/Options/TransactionsOverview.php");
        exit();

    case 'logout':
        require_once __DIR__ . "/../Options/Logout.php";
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="AdminDashboard.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <div class="container">
        <h1>Bank Söder – Admin</h1>
    </div>
    <div class="container">
        <img src="../../../Shared/Img/BankSöderLogoTop.png" alt="Söder logo" class="logo">
    </div>
</header>

<main>
    <div class="split_container">

        <div class="left"></div>

        <div class="container">
            <h1>Admin Dashboard</h1>

            <form class="form" method="post">
                <?php echo csrf_field(); ?>

                <button type="submit" name="type" value="accounts_overview">
                    Visa alla konton
                </button>

                <button type="submit" name="type" value="users_overview">
                    Visa alla användare
                </button>

                <button type="submit" name="type" value="edit_account">
                    Redigera konto
                </button>

                <button type="submit" name="type" value="edit_user">
                    Redigera användare
                </button>

                <button type="submit" name="type" value="transactions_overview">
                    Visa transaktioner
                </button>
                
                <button type="submit" name="type" value="logout">
                    Logga ut
                </button>

            </form>
            <a href="/Bankomat/Features/Kund/Dashboard/KundDashboard.php">⬅ Tillbaka</a>
        </div>

        <div class="right">
            <div>
                <?php
                    require_once __DIR__ . "/../Options/AdminOverview.php";
                    $overview = new AdminOverview();
                    $overview->execute();
                ?>
            </div>
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
