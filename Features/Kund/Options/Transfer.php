<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . "/../../../Shared/helpers.php";
    require_once __DIR__ . "/../../../Shared/Repository/KundRepository.php";
    require_once __DIR__ . "/../Options/DatabaseConnection.php";

    class Transfer {
        private KundRepository $kundRepository;
        private PDO $pdo;

        public function __construct() {
            $this->pdo = DatabaseConnection::getConnection();
            $this->kundRepository = new KundRepository($this->pdo);
        }

        public function execute() {
            global $messageToUser;

            if(isset($_POST['type'])) { 

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    csrf_verify();
                }

                if ($_POST['type'] === 'Avbryt') {
                    header("Location: /Bankomat/Features/Kund/Dashboard/KundDashboard.php");
                    exit();
                }

                if(($_POST['type'] === 'Överför')) {

                    $from = trim($_POST['from_account'] ?? '');
                    $to = trim($_POST['to_account'] ?? '');
                    $amount = trim($_POST['amount'] ?? '');

                    try {
                        if ($from === '' || $to === '' || $amount === '') {
                            throw new Exception("Alla fält måste fyllas i.");
                        }

                        if (!is_numeric($amount) || $amount <= 0) {
                            throw new Exception("Beloppet måste vara ett positivt nummer.");
                        }

                        $this->kundRepository->transfer(
                            (int)$from,
                            (int)$to,
                            (float)$amount
                        );

                        $messageToUser = "Överföring lyckades!";
                    } catch (Exception $e) {
                        $messageToUser = "Fel vid överföring: " . $e->getMessage();
                    }
                } 
                // else if (($_POST['type'] ?? '') === 'Avbryt') {
                //     header("Location: /Bankomat/Features/Kund/Dashboard/KundDashboard.php");
                //     exit();
                // }
            }
        }
    }
    $messageToUser ="";
    $Transfer = new Transfer();
    $Transfer->execute();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Överföring</title>
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
            <h1>Överföring</h1>
            <div class="container">
                <p><?php echo $messageToUser ?></p>
                <form class="form" method="post">
                    <?php echo csrf_field(); ?>

                    <label for="from_account">Avsändare:</label>
                    <input type="number" name="from_account" placeholder="Kontonummer" >

                    <label for="to_account">Mottagare:</label>
                    <input type="number" name="to_account" placeholder="Kontonummer" >

                    <label for="amount">Mängd:</label>
                    <input type="number" name="amount" placeholder="Belopp" >

                    <button type="submit" name="type" value="Överför">Överför</button>
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