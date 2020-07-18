<?php 
    session_start();
    require('dbconnect.php');
    if (!empty($_POST)) {
        if ($_POST['email'] === '') {
            $error['email'] = 'blank';
        }
        if ($_POST['password'] === '') {
            $error['password'] = 'blank';
        }
        if ($_POST['email'] !== '' && $_POST['password'] !== '') {
            $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
            $login->execute(array(
                $_POST['email'],
                sha1($_POST['password'])
            ));
            $member = $login->fetch();

            if ($member) {
                $_SESSION['id'] = $member['id'];
                $_SESSION['time'] = time();

                header('location: index.php');
                exit();
            } else {
                $error['login'] = 'blank';
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fav_Gazoログイン</title>
</head>

<body>
    <div class="container">
        <div>
            <h1>Fav_Gazoログイン画面</h1>
            <p><a href="./join/index.php">新規登録がまだの方はこちら</a></p>
            <form action="" method="post">
                <dl>
                    <dt>メールアドレス</dt>
                    <dd>
                        <input type="text" name="email" size="30" maxlength="30" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>">
                        <?php if ($error['email'] === 'blank'): ?>
                            <p class="error">*メールアドレスが入力されていません。</p>
                        <?php endif; ?>
                    </dd>
                    <dt>パスワード</dt>
                    <dd>
                        <input type="password" name="password" size="30" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
                        <?php if ($error['password'] === 'blank'): ?>
                            <p class="error">*パスワードが入力されていません。</p>
                        <?php endif; ?>
                        <?php if ($error['login'] === 'blank'): ?>
                            <p class="error">*指定されたメールアドレス、パスワードは正しくありません。</p>
                        <?php endif; ?>
                    </dd>
                    <dt>ログイン情報を保存する</dt>
                    <dd>
                        <input type="checkbox" name="save">
                        <label for="save">次回からは自動的にログインする</label>
                    </dd>
                </dl>
                <input type="submit" value="ログイン">
            </form>
        </div>
    </div>
</body>

</html>