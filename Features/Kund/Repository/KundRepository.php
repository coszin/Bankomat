<?php
    class KundRepository {
        private $conn;

        public function __construct(PDO $conn) {
            $this->conn = $conn;
        }

        public function findByCardNumber(string $cardNumber) {
            $stmt = $this->conn->prepare("SELECT * FROM accounts WHERE card_number = ?");
            $stmt->execute([$cardNumber]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>