<?php
    class KundRepository{
        private PDO $conn;

        public function __construct(PDO $conn) {
            $this->conn = $conn;
        }

        public function getAccountsByUserId(int $userId) {
            $stmt = $this->conn->prepare("SELECT * FROM accounts WHERE user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function getUserById(int $id) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getAccountsByKortnumer($kortnummer) {
            $stmt = $this->conn->prepare("SELECT * FROM accounts WHERE kortnummer = ?");
            $stmt->execute([$kortnummer]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getAllAccountNumbersWithNames(): array {
            $sql = "
                SELECT accounts.kortnummer, users.firstname, users.lastname
                FROM accounts
                JOIN users ON accounts.user_id = users.id
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAccountById(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM accounts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function withdraw(int $accountId, float $amount) {
        $stmt = $this->conn->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $accountId]);
    }

    public function deposit(int $accountId, float $amount) {
        $stmt = $this->conn->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $accountId]);
    }
    
    public function depositWithkortnummer(string $kortnummer, float $amount) {
        $stmt = $this->conn->prepare("UPDATE accounts SET balance = balance + ? WHERE kortnummer = ?");
        $stmt->execute([$amount, $kortnummer]);
        $account = $this->getAccountsByKortnumer($kortnummer);
        $this->logTransaction($account['id'], 2, $amount);
    }

    public function transfer(int $fromAccountKortnummer, int $toAccountKortnumer, float $amount) {

        try {
            $this->conn->beginTransaction();

            // Validate accounts
            $from = $this->getAccountsByKortnumer($fromAccountKortnummer);
            $to   = $this->getAccountsByKortnumer($toAccountKortnumer);

            if (!$from || !$to) {
                throw new Exception("Ett av kontona existerar inte.");
            }

            if ($from['balance'] < $amount) {
                throw new Exception("Otillräckligt saldo.");
            }

            // Perform transfer
            $this->withdraw($from['id'], $amount);
            $this->deposit($to['id'], $amount);

            // Log transaction
            $this->logTransfer($from['id'], $to['id'], 3, $amount);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function logTransfer(?int $fromAccountId, ?int $toAccountId, $type, float $amount) {
    $stmt = $this->conn->prepare("
        INSERT INTO transactions (from_account_id, to_account_id, type, amount)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$fromAccountId, $toAccountId, $type, $amount]);
    }

    public function logTransaction(?int $fromAccountId, $type, float $amount) {
    $stmt = $this->conn->prepare("
        INSERT INTO transactions (from_account_id, type, amount)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$fromAccountId, $type, $amount]);
    }

    public function getTransactionsForAccount(int $accountId): array {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM transactions
            WHERE from_account_id = ? OR to_account_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$accountId, $accountId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function withdrawal(string $kortnummer, float $amount) {
        $this->conn->beginTransaction();

        // Check balance
        $stmt = $this->conn->prepare(
            "SELECT balance FROM accounts WHERE kortnummer = :kortnummer FOR UPDATE"
        );
        $stmt->execute([':kortnummer' => $kortnummer]);
        $saldo = $stmt->fetchColumn();

        if ($saldo === false) {
            $this->conn->rollBack();
            throw new Exception("Kontot hittades inte.");
        }

        if ($saldo < $amount) {
            $this->conn->rollBack();
            throw new Exception("Otillräckligt saldo.");
        }

        $account = $this->getAccountsByKortnumer($kortnummer);
        $this->logTransaction($account['id'], 1, $amount);
        // Withdraw
        $stmt = $this->conn->prepare(
            "UPDATE accounts SET balance = balance - :amount WHERE kortnummer = :kortnummer"
        );
        $stmt->execute([
            ':amount' => $amount,
            ':kortnummer' => $kortnummer
        ]);

        $this->conn->commit();
    }


}
?>