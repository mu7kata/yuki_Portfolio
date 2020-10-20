<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

if (!empty($_POST)) {
  debug('POST送信あり');
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  vaildRequired($pass, 'pass');
  vaildEmail($email, 'email');
  validpass($pass, 'pass');

  if (empty($err_msg)) {
    debug('バリデーションOK');

    try {

      $dbh = dbConnect();
      $sql = 'SELECT password , id FROM users WHERE email = :email AND delete_flg=0';
      $data = array(':email' => $email);
      $stmt = queryPost($dbh, $sql, $data);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      debug('クエリ結果の中身：' . print_r($result, true));

      if (!empty($result) && password_verify($pass, array_shift($result))) {
        $sesLimit = 60*60;
        $_SESSION['login_limit'] = $sesLimit;
        $_SESSION['login_date'] = time();
        $_SESSION['user_id'] = $result['id'];
        debug('セッション変数の中身：' . print_r($_SESSION, true));
        debug('マイページへ遷移します。');
        header("Location:index.php");
      } else {
        debug('パスワードが間違っています');
        $err_msg['common'] = MSG10;
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

    <section>

      <div class="form-container">

        <form action="" method="post" class="login-form">
          <h2 class="title">LOGIN</h2>

          <p>メールアドレス</p>
          <div class="err_msg"><?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?></div>
          <input type="text" name="email" value="gest@icloud.com">
          </label>
          <label class="pass">
            <p>パスワード</p>
            <div class="err_msg"><?php if (!empty($err_msg['pass'])) echo $err_msg['pass']; ?></div>
            <input type="password" name="pass" value="gestgest">
          </label>
          <input type="submit" class="btn btn-mid" value="ログイン">
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
</style>