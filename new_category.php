<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('カテゴリ新規作成ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
$getcategory = getcategory();
$user_id = $_SESSION['user_id'];
$new_category = $_POST['new_category'];

if (!empty($new_category)) {
  debug('カテゴリを新規登録します');
  try {
    $dbh = dbConnect();
    $sql = 'INSERT INTO category(user_id,category_name,createdate) VALUES(:user_id,:new_category,:createdate)';
    $data = array('user_id' => $user_id, ':new_category' => $new_category, ':createdate' => date('Y-m-d-H-i'));
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {

      $_SESSION['msg_succes'] = MSG12;
      debug('カテゴリ編集ページへ遷移します。');
      session_write_close();
      header("Location:FillOut.php");
      exit();
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
  <title>学習記録ページ</title>
</head>

<body>

  <?php
  require('header.php');

  ?>

  <main>

    <div class="site-width2">

      <section class="category">
        <div class="page-title">
          <h1 class="page-title">カテゴリを新規作成</h1>
        </div>
        <form method="post">
          <div class="selectbox">
            <p>新しいカテゴリ名を記入してください</p>
            <input type="text" name="new_category" placeholder="カテゴリ名を記入">

            </select>
          </div>
      </section>

      <div class='btn-container'>
        <input type="submit" value="新規登録">
      </div>
      </form>

    </div>

    <a class="i_jump" href="FillOut.php">記録ページへ戻る</a>
  </main>
</body>
<style>
  input ::placeholder {
    color: #FF773E;
  }

  main {
    background-color: #ddd;
    height: 900px;
  }

  form {
    margin: 0 auto;
    padding: 5px;
    width: 400px;
    border: 5px solid rgb(0, 0, 0, 0);
    display: block;
    height: 160px;
  }

  .i_jump {
    display: block;
    margin: 0 auto;
    margin-top: 50px;
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

  h1 {
    font-size: 16px;
    display: block;
    margin-bottom: 10px;
    letter-spacing: 3px;
  }

  input[type="submit"] {

    padding: 15px 30px;
    width: 150px;
    border: none;
    background: #FF773E;
    color: white;
    font-size: 14px;
    cursor: pointer;
    display: block;
    margin: 0 auto;

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
</style>