<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    require_once __DIR__ . "/../../../Shared/helpers.php";
    require_once __DIR__ . "/../../../Shared/Repository/KundRepository.php";
    require_once __DIR__ . "/../Options/DatabaseConnection.php";

    class Overview {
        private KundRepository $KundRepository;
        private PDO $pdo;

        public function __construct() {
            $this->pdo = DatabaseConnection::getConnection();
            $this->KundRepository = new KundRepository($this->pdo);
        }

        public function execute() {
            if (!isset($_SESSION['user_id'])) {
                header("Location: /Bankomat/Features/Kund/Login/Login.php");
                exit();
            }

            $userId = $_SESSION['user_id'];
            $accounts = $this->KundRepository->getAccountsByUserId($userId);

            echo "<h2>Dina Konton</h2>";
            foreach ($accounts as $account) {
                echo "kortnummer: " . htmlspecialchars($account['kortnummer']) . " - Saldo: " . htmlspecialchars($account['balance']) . " kr<br>" . "<br>";
            }
        }

        public function getAccounts(): array {
            return $this->KundRepository->getAllAccountNumbersWithNames();
        }
    }
?>