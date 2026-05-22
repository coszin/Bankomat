<?php
    class Repository {
        protected PDO $conn;

        function __construct(PDO $conn) {
            $this->conn = $conn;
        }
    }
?>