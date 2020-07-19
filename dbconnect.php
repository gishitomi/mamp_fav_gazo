<?php
$dbname = 'fav_gazo';
$host = 'localhost';
$charset = 'utf8';
$username  = 'root';
$password = 'root';

// $host = getenv('HOSTNAME'); //MySQLがインストールされてるコンピュータ
// $dbname = getenv('DBNAME'); //使用するDB
// $charset = "utf8"; //文字コード
// $username = getenv('USERNAME'); //MySQLにログインするユーザー名
// $password = getenv('PASSWORD'); //ユーザーのパスワード
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //SQLでエラーが表示された場合、画面にエラーが出力される
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //DBから取得したデータを連想配列の形式で取得する
    PDO::ATTR_EMULATE_PREPARES   => false, //SQLインジェクション対策
];


$sql = "mysql:dbname=$dbname;host=$host;charset=$charset";
try {
    $db = new PDO($sql, $username, $password, $options);
} catch(PDOException $e) {
    print('DB接続エラー:' . $e->getMessage());
}