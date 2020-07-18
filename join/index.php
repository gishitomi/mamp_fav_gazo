<?php
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {
    if ($_POST['nickname'] === '') {
        $error['nickname'] = 'blank';
    }
    if ($_POST['email'] === '') {
        $error['email'] = 'blank';
    }
    if ($_POST['password'] === '') {
        $error['password'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }
    $fileName = $_FILES['image']['name'];
    if (!empty($fileName)) {
        $ext = substr($fileName, -3);
        $ext2 = substr($fileName, -4);
        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png' && $ext2 !== 'jpeg') {
            $error['image'] = 'type';
        }
    }
    // アカウントの重複をチェック
    if (empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }

    if (empty($error)) {
        // 投稿時の時刻、ファイル名をくっつけて保管
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            '../member_picture/' . $image
        );
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location: check.php');
        exit();
    }
}

// check.phpから戻ってきた場合
if ($_REQUEST['action'] === 'rewrite' && isset($_SESSION['join'])) {
    $_POST = $_SESSION['join'];
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fav-Gazo新規登録</title>
</head>

<body>
    <div class="container">
        <h1>Fav_Gazoへようこそ！</h1>
        <p>みんなが画像を投稿しあって、シェアするアプリです。</p>
        <h1>新規登録</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <dl>
                <dt>ニックネーム<span class="error">*必須</span></dt>
                <dd><input type="text" name="nickname" maxlength="50" size="30" value="<?php print(htmlspecialchars($_POST['nickname'], ENT_QUOTES)); ?>">
                    <?php if ($error['nickname'] === 'blank') : ?>
                        <p class="error">*ニックネームが入力されていません。</p>
                    <?php endif; ?>
                </dd>
                <dt>メールアドレス<span class="error">*必須</span></dt>
                <dd><input type="text" name="email" size="30" maxlength="30" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>">
                    <?php if ($error['email'] === 'blank') : ?>
                        <p class="error">*メールアドレスが入力されていません。</p>
                    <?php endif; ?>
                    <?php if ($error['email'] === 'duplicate') : ?>
                        <p class="error">*入力されたメールアドレスはすでに登録済みです。</p>
                    <?php endif; ?>
                </dd>
                <dt>パスワード<span class="error">*必須</span></dt>
                <dd><input type="password" name="password" size="30" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
                    <?php if ($error['password'] === 'blank') : ?>
                        <p class="error">*パスワードが入力されていません。</p>
                    <?php endif; ?>
                    <?php if ($error['password'] === 'length') : ?>
                        <p class="error">*パスワードは4文字以上入力してください。</p>
                    <?php endif; ?>
                </dd>
                <dt>プロフィール画像</dt>
                <dd>
                    <input type="file" name="image" size="35" value="test" />
                    <?php if ($error['image'] === 'type') : ?>
                        <p class="error">*画像の形式が適切ではありません。</p>
                    <?php endif; ?>
                    <?php if (empty($error)): ?>
                        <p class="error">*お手数ですが、もう一度入力し直してください。</p>
                    <?php endif; ?>
                </dd>
            </dl>
            <div>
                <input type="submit" value="入力内容を確認する">
            </div>
        </form>
    </div>
</body>

</html>