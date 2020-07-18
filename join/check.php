<?php
session_start();
require('../dbconnect.php');

if (!isset($_SESSION['join'])) {
    header('location: index.php');
    exit();
}

if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET nickname=?, email=?, password=?, picture=?, created=NOW()');
    $statement->execute(array(
        $_SESSION['join']['nickname'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ));
    unset($_SESSION['join']);
    header('location: thanks.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録内容の確認</title>
</head>
<body>
    <div class="container">
        <h1>登録内容の確認</h1>
        <p>以下の内容でよろしいでしょうか。確認後「登録する」ボタンをクリックしてください。</p>
        <form action="" method="post">
            <input type="hidden" name="action" value="submit">
            <dl>
                <dt>ニックネーム</dt>
                <dd>
                    <?php print(htmlspecialchars($_SESSION['join']['nickname'])); ?>
                </dd>
                <dt>メールアドレス</dt>
                <dd>
                <?php print(htmlspecialchars($_SESSION['join']['email'])); ?>
                </dd>
                <dt>プロフィール画像</dt>
                <dd>
                    <?php if ($_SESSION['join']['image'] !== ''): ?>
                        <img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'])); ?>" alt="" style="width: 100%;">
                    <?php endif; ?>
                </dd>
            </dl>
            <div>
                <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
                |
                <input type="submit" value="登録する">
            </div>
        </form>
    </div>
</body>
</html>