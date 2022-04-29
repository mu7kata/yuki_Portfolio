<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ユーザー登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if (!empty($_POST)) {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_r = $_POST['pass_r'];

    vaildRequired($user_name, 'user_name');
    validMaxLen($user_name, 'user_name');
    vaildRequired($pass, 'pass');
    vaildRequired($pass_r, 'pass_r');
    vaildEmail($email, 'email');
    validpass($pass, 'pass');
    validpass($pass_r, 'pass_r');
    validMatch($pass, $pass_r, 'pass_r');
    // vaiildDup('user_name',$user_name);
    // vaiildDup('email',$email);

    if (empty($err_msg)) {

        try {
            $dbh = dbConnect();
            $sql = 'INSERT INTO users(email,password,user_name,login_time,create_date)
      VALUES(:email,:pass,:user_name,:login_time,:create_date)';
            $data = array(
                ':email' => $email,
                ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                ':user_name' => $user_name,
                ':login_time' => date('Y-m-d H:i:s'),
                ':create_date' => date('Y-m-d H:i:s'));
            $stmt = queryPost($dbh, $sql, $data);

            if ($stmt) {

                $sesLimit = 60 * 60;
                $_SESSION['login_date'] = time();
                $_SESSION['login_limit'] = $sesLimit;
                $_SESSION['user_id'] = $dbh->lastInsertId();
                debug('セッション変数の中身：' . print_r($_SESSION, true));
                header("Location:index.php");
                exit();
            }
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }

}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<header>
    <div>
        <div class="first_header">
            <h1>学習日誌くん</h1>
        </div>
    </div>
</header>
<main>
    <div class="site-width">


        <!-- Main -->
        <section>

            <div class="form-container">

                <form action="" method="post" class="login-form">
                    <h2 class="title">新規登録</h2>

                    <label class="user_name">
                        <p>アカウント名</p>
                        <div class="err_msg"><?php if (!empty($err_msg['user_name'])) echo $err_msg['user_name']; ?></div>
                        <input type="text" name="user_name" value="">
                    </label>
                    <label class="email">
                        <p>メールアドレス</p>
                        <div class="err_msg"><?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?></div>
                        <input type="text" name="email" value="">
                    </label>
                    <label class="pass">
                        <p>パスワード</p>
                        <div class="err_msg"><?php if (!empty($err_msg['pass'])) echo $err_msg['pass']; ?></div>
                        <input type="password" name="pass" value="">
                    </label>
                    <label class="pass_r">
                        <p>パスワード再入力</p>
                        <div class="err_msg"><?php if (!empty($err_msg['pass_r'])) echo $err_msg['pass_r']; ?></div>
                        <input type="password" name="pass_r" value="">
                    </label>
                    <input type="submit" class="btn btn-mid" value="新規登録！">
                </form>
            </div>
        </section>
        <a class="i_jump" href="TopPage.php">トップページへ戻る</a>
</main>

<style>
    main {
        background-color: #ddd;
        height: 1200px;
    }

    form {
        margin: 0 auto;
        padding: 15px;
        width: 400px;
        border: 5px solid rgb(0, 0, 0, 0);
        display: block;
    }

    input[type="text"],
    input[type="password"] {
        display: block;
        box-sizing: border-box;
        margin: 10px 1px;
        padding: 5px;
        width: 380px;
        height: 60px;
        font-size: 18px;
    }

    input[type="submit"] {
        margin: 15px 125px;
        padding: 15px 30px;
        width: 150px;
        border: none;
        background: #FF773E;
        color: white;
        font-size: 14px;
        cursor: pointer;
    }

    h2 {
        display: block;
        font-size: 45px;
        font-weight: bold;
        letter-spacing: 3px;
        margin: 0;
    }

    .title {
        margin-bottom: 40px;
        text-align: center;
    }

    label p {
        margin-top: 5px;
        float: left;
    }
</style>