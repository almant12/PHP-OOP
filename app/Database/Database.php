<?php 

class Database{


    public static $pdo;


    public static function getConnection(){
        $dsn = 'mysql:hsot=localhost;dbname=scandiweb';
        $user = 'root';
        $password = 'almant';

        try{
            self::$pdo = new PDO($dsn,$user,$password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = '
            CREATE TABLE IF NOT EXISTS `products` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `sku` VARCHAR(30) NOT NULL UNIQUE,
                `name` VARCHAR(30) NOT NULL,
                `price` DECIMAL(10, 2) NOT NULL,
                `type` ENUM(\'DVD\', \'BOOK\', \'FURNITURE\') NOT NULL,
                `attributes` VARCHAR(30) NOT NULL
            );
        ';
            self::$pdo->query($query)->execute();

        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }

        return self::$pdo;
    }
}