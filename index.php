<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array(
        $_SESSION['id']
    ));
    $member = $members->fetch();
} else {
    header('location: login.php');
    exit();
}

if (!empty($_POST)) {
    if ($_POST['message'] !== '' && $_POST['gazo'] !== '') {
        $post = $db->prepare('INSERT INTO posts SET message=?, gazo=?, reply_message_id=?, created=NOW()');
        $post->execute(array(
            $_POST['message'],
            $_POST['gazo'],
            $_POST['reply_message_id']
        ));
        header('location: index.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fav_Gazo</title>
</head>

<body>
    <div class="container">
        <h1>ようこそ!!Fav_Gazoへ</h1>
        <h2><?php print(htmlspecialchars($member['nickname'], ENT_QUOTES)); ?>さん、なにか投稿してみましょう！！</h2>
        <form action="" method="post">
            <textarea name="massage" id="message" cols="90" rows="5" placeholder="なにしてる？？"></textarea>
            <div>
                <input type="file" name="gazo" size="35">
            </div>

            <input type="hidden" name="reply_message_id">
            <div>
                <input type="submit" value="投稿する">
            </div>
        </form>

        <div>
            <a href="logout.php">ログアウト</a>
        </div>

    </div>
</body>

</html>