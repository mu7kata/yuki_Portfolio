<?php
require('function.php');
require('auth.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「カテゴリ編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
$getcategory = getcategory();

$user_id = $_SESSION['user_id'];
$postcateogry = (!empty($_POST['category-list'])) ? $_POST['category-list'] : '';
debug('編集したいカテゴリ:' . $postcateogry);
(!empty($_POST['time-list'])) ? $_POST['time-list'] : '';
$recategorylist = (!empty($_POST['recategorylist'])) ? $_POST['recategorylist'] : '';
debug('変更したいカテゴリ:' . $recategorylist);
$postrecateogry =  (!empty($_POST['rename_category'])) ? $_POST['rename_category'] : '';
debug('変更後のカテゴリ:' . $postrecateogry);

if ($postcateogry === 'new') {
  debug('カテゴリ作成ページへ遷移します。');
  header("Location:new_category.php");
} else {
  $_SESSION['category'] = $postcateogry;
}
if (!empty($postrecateogry)) {
  debug('カテゴリ名を変更します');
  try {
    $dbh = dbConnect();
    $sql = 'UPDATE category set category_name= :recateogry WHERE user_id= :user_id and category_name=:category_name ';
    $data = array(':recateogry' => $postrecateogry, 'user_id' => $user_id, ':category_name' => $recategorylist);
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      $_SESSION['msg_succes'] = MSG12;
      debug('カテゴリ編集ページへ遷移します。');
      session_write_close();
      header("Location:FillOut.php");
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>カテゴリ編集ページ</title>
</head>

<body>

  <?php
  require('header.php');

  ?>

  <main>

    <div class="site-width2">
      <?php if (empty($_SESSION['category'])) { ?>
        <div class="page-title">
          <h1 class="page-title"><img class='icon' src="img/setting.png" alt="">カテゴリを編集する</h1>
        </div>
        <form method="post">
          <section class="category">
            <div class="selectbox">
              <h1>編集したいカテゴリ、<br>または新規作成を選択</span></h1>
              <select name="category-list">
                <option value="0">選択してください</option>
                <option value="new">※新規作成※</option>
                <?php
                foreach ($getcategory as $key => $val) {
                ?>
                  <option value="<?php echo $val['category_name'] ?>"><?php echo $val['category_name'] ?></option><?php  } ?>

              </select>
            </div>
          </section>

          <div class='btn-container'>
            <input type="submit" value="次へ">
          </div>
        </form>
      <?php } else { ?>
        <div class="page-title">
          <h1 class="page-title">カテゴリを変更する</h1>
        </div>
        <form method="post">
          <section class="category">
            <div class="selectbox">
              <h1>カテゴリを再登録してください</span></h1>
              <p>1.変更したいカテゴリ</p>
              <select name="recategorylist">

                <?php
                foreach ($getcategory as $key => $val) {
                ?>
                  <option value="<?php echo $val['category_name']; ?>">
                    <!-- <?php if ($val['category_name'] === $_SESSION['category']) {
                            echo 'selected';
                          } ?> -->
                    <?php echo $val['category_name'] ?></option>
                <?php  } ?>
              </select>
            </div>
            <p>2.変更後のカテゴリ</p>
            <input type="text" name="rename_category">
          </section>
          <div class='btn-container'>
            <input type="submit" value="再登録">
          </div>
        </form>

      <?php } ?>
    </div>

    <a class="i_jump" href="FillOut.php">記録ページへ戻る</a>
  </main>
</body>
<style>
  main {
    background-color: #ddd;
    height: 900px;
    width: 100%;
  }

  form {
    margin: 0 auto;
    padding: 5px;
    width: 400px;
    border: 5px solid rgb(0, 0, 0, 0);
    display: block;
    height: 400px;
  }

  .i_jump {
    display: block;
    margin: 0 auto;
    padding: 10px;
    width: 130px;
  }

  select {
    display: block;
    box-sizing: border-box;
    margin: 10px 1px;
    padding: 5px;
    width: 380px;
    height: 60px;
    font-size: 18px;
  }

  .selectbox h1 {
    font-size: 24px;
    display: block;
    margin-bottom: 30px;
    border-bottom: 2px solid;
    letter-spacing: 3px;
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

  .page-title {
    margin-bottom: 10px;

    text-align: center;
    font-size: 35px;
    font-weight: bold;
    letter-spacing: 5px;
  }

  .site-width2 {
    margin: 0 auto;
    width: 980px;
    padding-bottom: 10px;
    padding-top: 10px;
  }

  .add_Category a {
    font-size: 13px;
    color: #333;

  }

  .category p {
    font-size: 18px;
  }
</style>