<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「設定ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
$_SESSION['file'] =  basename(__FILE__);
$user_id = $_SESSION['user_id'];




?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ホーム</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <?php
  require('header.php');
  require('auth.php');

  ?>
  <main>
    <div class="site-width2">
      <div class="page-title">
        <p><img class='icon' src="img/setting.png" alt="">設定の変更</p>
      </div>
      <section class="contents_butan">
        <div class="contents1">
          <a href="FillOut.php">
            <p>ユーザー名変更</p>
          </a>
        </div>
        <div class="contents2">
          <a href="ReadBack.php">
            <p>退会</p>
          </a>
        </div>
      </section>
    </div>

  </main>
  <?php require('footer.php'); ?>
</body>
<style>
  .site-width2 {
    margin: 0 auto;
    width: 980px;
    padding-bottom: 80px;
    padding-top: 10px;
  }

  .contents_butan {
    margin: 0 auto;
    display: block;
    width: 260px;
    font-size: 30px;
  }

  .page-title {
    margin-bottom: 10px;

    text-align: center;
    font-size: 35px;
    font-weight: bold;
    letter-spacing: 5px;
  }
</style>

</html>