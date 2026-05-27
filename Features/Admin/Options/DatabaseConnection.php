<?php
class DatabaseConnection {
    public static function getConnection(): PDO {
        return new PDO('mysql:host=localhost;dbname=banksouth', 'root', '');
    }
}
?>