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

    if ($_POST['message'] !== '') {

        $fileName = $_FILES['gazo']['name'];
        if (!empty($fileName)) {
            $ext = substr($fileName, -3);
            $ext2 = substr($fileName, -4);
            if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png' && $ext2 !== 'jpeg') {
                $error['gazo'] = 'type';
            }
        }
        if (empty($error)) {
            // 投稿時の時刻、ファイル名をくっつけて保管
            $image = date('YmdHis') . $_FILES['gazo']['name'];
            move_uploaded_file(
                $_FILES['gazo']['tmp_name'],
                './fav_gazos/' . $image
            );



            $message = $db->prepare('INSERT INTO posts SET message=?, gazo=?, member_id=?, reply_message_id=?, created=NOW()');
            $message->execute(array(
                $_POST['message'],
                $image,
                $member['id'],
                $_POST['reply_message_id'],
            ));
            header('location: index.php');
            exit();
        }
    }
}
// リレーション
$posts = $db->query('SELECT m.nickname, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fav_Gazo</title>
</head>
<style>
    @charset "UTF-8";

    .contents {
        display: flex;
    }
    .comment {
        margin-left: 20px;
    }
    .comment img {
        width: 20%;
    }
</style>

<body>
    <div class="container">
        <h1>ようこそ!!Fav_Gazoへ</h1>
        <h2><?php print(htmlspecialchars($member['nickname'], ENT_QUOTES)); ?>さん、なにか投稿してみましょう！！</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <textarea name="message" id="message" cols="90" rows="5" placeholder="なにしてる？？"></textarea>
            <div>
                <input type="file" name="gazo" size="35">
                <?php if ($error['gazo'] === 'type') : ?>
                    <p class="error">*適切な形式ではありません。</p>
                <?php endif; ?>
            </div>
            <input type="hidden" name="reply_message_id">
            <div>
                <input type="submit" value="投稿する">
            </div>
        </form>
        <div class="articles">
            <hr>
            <?php foreach ($posts as $post) : ?>
                <div class="contents">
                    <div class="profile">
                        <img src="./member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" alt="">
                        <p><?php print(htmlspecialchars($post['nickname'])); ?></p>
                    </div>
                    <div class="comment">
                        <img src="./fav_gazos/<?php print(htmlspecialchars($post['gazo'], ENT_QUOTES)); ?>" alt="">
                        <p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?></p>
                        <div class="date">
                            <p><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></p>
                        </div>
                    </div>
                </div>


                <hr>
            <?php endforeach; ?>
        </div>


        <div>
            <a href="./login.php">ログアウト</a>
        </div>

    </div>
</body>

</html>