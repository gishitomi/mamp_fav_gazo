<?php
$dbname = 'fav_gazo';
$host = 'localhost';
$charset = 'utf8';
$username  = 'root';
$password = 'root';
$sql = "mysql:dbname=$dbname;host=$host;charset=$charset";
try {
    $db = new PDO($sql, $username, $password);
} catch(PDOException $e) {
    print('DB接続エラー:' . $e->getMessage());
}