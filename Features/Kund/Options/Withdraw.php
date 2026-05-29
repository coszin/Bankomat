<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../../Shared/helpers.php";
require_once __DIR__ . "/../../../Shared/Repository/KundRepository.php";
require_once __DIR__ . "/../Options/DatabaseConnection.php";

class Withdrawal {
    private KundRepository $kundRepository;
    private PDO $pdo;

    public function __construct() {
        $this->pdo = DatabaseConnection::getConnection();
        $this->kundRepository = new KundRepository($this->pdo);
    }

    public function execute() {
        global $messageToUser;

        if (!isset($_POST['type'])) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_verify();
        }

        if ($_POST['type'] === 'Uttag') {

            $account = trim($_POST['account'] ?? '');
            $amount  = trim($_POST['amount'] ?? '');

            try {
                if ($account === '' || $amount === '') {
                    throw new Exception("Alla fält måste fyllas i.");
                }

                if (!is_numeric($amount) || $amount <= 0) {
                    throw new Exception("Beloppet måste vara ett positivt nummer.");
                }

                $this->kundRepository->withdrawal(
                    (string)$account,
                    (float)$amount
                );

                $messageToUser = "Uttag lyckades!";
            } catch (Exception $e) {
                $messageToUser = "Fel vid uttag: " . $e->getMessage();
            }

        } else if ($_POST['type'] === 'Avbryt') {
            header("Location: /Bankomat/Features/Kund/Dashboard/KundDashboard.php");
            exit();
        }
    }
}

$messageToUser = "";
$withdrawal = new Withdrawal();
$withdrawal->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uttag</title>
    <link rel="stylesheet" href="/Bankomat/public/Index.css?v=<?php echo time(); ?>">
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
    <h1>Uttag</h1>
    <div class="container">
        <p><?php echo $messageToUser ?></p>

        <form class="form" method="post">
            <?php echo csrf_field(); ?>

            <label for="account">Konto:</label>
            <input type="number" name="account" placeholder="Kontonummer"
                   value="<?php echo htmlspecialchars($_POST['account'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <label for="amount">Belopp:</label>
            <input type="number" name="amount" placeholder="Belopp"
                   value="<?php echo htmlspecialchars($_POST['amount'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <button type="submit" name="type" value="Uttag">Ta ut</button>
            <button type="submit" name="type" value="Avbryt">Avbryt</button>
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
