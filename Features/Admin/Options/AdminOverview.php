<?php

class AdminOverview {

    public function execute() {

        require_once __DIR__ . '/DatabaseConnection.php';

        $factory = new DatabaseConnection();
        $conn = $factory->getConnection();

        // Fetch counts
        $totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalAccounts = $conn->query("SELECT COUNT(*) FROM accounts")->fetchColumn();
        $totalBalance = $conn->query("SELECT SUM(balance) FROM accounts")->fetchColumn();
        $totalTransactions = $conn->query("SELECT COUNT(*) FROM transactions")->fetchColumn();

        // Fetch last 5 transactions
        $stmt = $conn->query("
            SELECT 
                t.id,
                fa.kortnummer AS from_acc,
                ta.kortnummer AS to_acc,
                t.amount,
                t.type,
                t.created_at
            FROM transactions t
            LEFT JOIN accounts fa ON t.from_account_id = fa.id
            LEFT JOIN accounts ta ON t.to_account_id = ta.id
            ORDER BY t.created_at DESC
            LIMIT 5
        ");
        $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Admin Översikt</h2>";

        echo "<p><strong>Totalt antal användare:</strong> $totalUsers</p>";
        echo "<p><strong>Totalt antal konton:</strong> $totalAccounts</p>";
        echo "<p><strong>Totalt saldo i banken:</strong> " . number_format($totalBalance, 2) . " kr</p>";
        echo "<p><strong>Antal transaktioner:</strong> $totalTransactions</p>";

        echo "<h3>Senaste 5 transaktionerna</h3>";

        echo "<table border='1' cellpadding='6'>
                <tr>
                    <th>ID</th>
                    <th>Från konto</th>
                    <th>Till konto</th>
                    <th>Belopp</th>
                    <th>Typ</th>
                    <th>Datum</th>
                </tr>";

        foreach ($recent as $t) {
            echo "<tr>
                    <td>{$t['id']}</td>
                    <td>" . ($t['from_acc'] ?: '-') . "</td>
                    <td>" . ($t['to_acc'] ?: '-') . "</td>
                    <td>{$t['amount']}</td>
                    <td>{$t['type']}</td>
                    <td>{$t['created_at']}</td>
                </tr>";
        }

        echo "</table>";
    }
}