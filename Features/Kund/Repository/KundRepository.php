<?php
    class KundRepository {
        private $conn;

        public function __construct(PDO $conn) {
            $this->conn = $conn;
        }

        public function getAccountsByUserId(int $userId) {
            $stmt = $this->conn->prepare("SELECT * FROM accounts WHERE user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function withdraw(int $accountId, float $amount) {
            $stmt = $this->conn->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
            return $stmt->execute([$amount, $accountId]);
        }

        public function deposit(int $accountId, float $amount) {
            $stmt = $this->conn->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
            return $stmt->execute([$amount, $accountId]);
        }

        public function transfer(int $fromAccountId, int $toAccountId, float $amount) {
            try {
                $this->conn->beginTransaction();
                $this->withdraw($fromAccountId, $amount);
                $this->deposit($toAccountId, $amount);
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollBack();
                return false;
            }
        }
    }
?>