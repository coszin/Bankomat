    <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            require "/Bankomat/Shared/Infrastructure/database.php";
            $dbFactory = new DatabaseFactory();
            $conn = $dbFactory->createDatabaseConnection();

            $stmt = $conn->prepare("INSERT INTO Customers (firstname, lastname, email, dateofbirth, national_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                explode(" ", $_POST['name'])[0],
                explode(" ", $_POST['name'])[1],
                $_POST['email'],
                $_POST['dateofbirth'],
                $_POST['national_id']
            ]);

            echo "Account created successfully!";
        }

        $stmt = $conn->query("INSERT INTO Accounts (customer_id, account_type, balance, currency, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $conn->lastInsertId(),
            1,
            0.00,
            'SEK',
            'active'
        ]);
    ?>