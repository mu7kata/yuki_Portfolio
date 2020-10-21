<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
$_SESSION['file'] =  basename(__FILE__);
$user_id = $_SESSION['user_id'];

if (!empty($_POST['memo'])) {
  $_SESSION['memo'] = $_POST['memo'];
}
$memo = '';
$memo = $_SESSION['memo'];
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
      <section class="contents_butan">
        <div class="contents1">
          <a href="FillOut.php">
            <p><img class='icon' src="img/pen.png" alt="">学習を記録する </p>

          </a>
        </div>
        <div class="contents2">
          <a href="ReadBack.php">
            <p><img class='icon' src="img/book.png" alt=""> 学習を振り返る </p>
          </a>
        </div>
      </section>
      <section　class='memo'>
        <div class="memo_space">
          <h1>メモ</h1>
          <form method="post">
            <textarea name="memo" id="" cols="50" rows="12"><?php echo $memo; ?></textarea>
            <input type="submit" name="save" id="" value="メモを保存">
          </form>

        </div>
      </section>
    </div>
    <?php
    require('StudyDetail.php');
    ?>

  </main>
  <?php require('footer.php'); ?>
</body>
<style>
  .contents_butan {
    float: right;
    width: 436px;

  }

  .contents_butan p {
    margin: 0;
  }

  .contents_butan a {
    display: block;
    height: 81px;
    padding-top: 32px;
    font-size: 32px;
    text-decoration: none;
    text-align: center;
    color: #333;
  }

  .contents1 {
    border-radius: 20px;
    border: 1px solid #333;
    width: 300px;
    margin-bottom: 42px;
    margin-top: 15px;
    background-color: #F0FFFF;
    box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .5);
  }


  .contents2 {
    border-radius: 20px;
    border: 1px solid #333;
    width: 300px;
    background-color: #F0FFFF;
    box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .5);
  }


  .memo_space {
    height: 200px;
    width: 350px;
    margin-left: 83px;

  }

  textarea {
    font-size: 28px;
    height: 208px;
    width: 368px;
  }

  .memo_space h1 {
    margin: 0;
  }

  .site-width2 {
    margin: 0 auto;
    width: 980px;
    padding-bottom: 170px;
    padding-top: 60px;
  }
</style>

</html>