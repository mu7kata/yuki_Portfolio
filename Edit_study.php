<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「学習内容編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
$getcategory = getcategory();
$study_id = $_SESSION['Edit_study_id'];
$user_id = $_SESSION['user_id'];
$geteditstudy = geteditstudy($user_id, $study_id);
debug('編集する学習内容' . print_r($geteditstudy, true));
debug('POSTの中身：' . print_r($_POST, true));
$study_time = (!empty($_POST['time-list'])) ? $_POST['time-list'] : '';
$study_month = (!empty($_POST['month'])) ? sprintf('%02d', $_POST['month']) : '';
$study_year = (!empty($_POST['year'])) ? $_POST['year'] : '';
$study_date = (!empty($_POST['day'])) ? $study_year . '-' . $study_month . '-' . $_POST['day'] : '';
$study_category = (!empty($_POST['category-list'])) ? $_POST['category-list'] : '';
$study_detail = (!empty($_POST['study-detail'])) ? $_POST['study-detail'] : '';


if (!empty($_POST)) {
  debug('学習内容を編集します');
  try {
    $dbh = dbConnect();
    $sql = 'UPDATE study_detail
    set 
    study_time=:study_time,
    study_category=:study_category,
    study_detail=:study_detail,
    study_year=:study_year,
    study_month	=:study_month	,
    study_date=:study_date,
    update_date=:update_date
    WHERE id= :study_id ';
    $data = array(
      ':study_time' => (int)$study_time,
      ':study_category' => $study_category,
      ':study_detail' => $study_detail,
      ':study_year' => $study_year,
      ':study_month' =>  $study_month,
      ':study_date' => $study_date,
      ':update_date' => date('Y-m-d-H-i'), ':study_id' => $study_id
    );
    debug('変更予定の中身：' . print_r($data, true));
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      $_SESSION['msg_succes'] = MSG13;
      debug('学習内容を変更しました。');
      session_write_close();
      header('location:ReadBack.php');
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
  <title>学習履歴編集ページ</title>
</head>

<body>

  <?php
  require('header.php');
  require('auth.php');
  ?>

  <main>

    <div class="site-width2">
      <div class="page-title">
        <h1 class="page-title"><img class='icon' src="img/book.png" alt="">学習履歴を編集する</h1>
      </div>
      <form action="" method="post">
        <section class="date">
          <div class="selectbox">
            <h1>日付を選択</h1>
            <select class="year-list" name="year" id="">
              <option value="<?php echo date('Y'); ?>"><?php echo date('Y') . '年'; ?></option>
              <?php for ($i = date('Y') - 1; $i >= 2010; $i--) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i . '年'; ?></option><?php } ?>
              <?php if (!empty($geteditstudy['$study_year'])) { ?>
                <option value="<?php echo $$geteditstudy['$study_year'] ?>" <?php if (!empty($geteditstudy['$study_year'])) echo 'selected'; ?>>
                  <?php echo $$geteditstudy['$study_year'] ?></option>
              <?php } ?>

            </select>
            <select class="month-list" name="month">

              <?php for ($i = 1; $i <= 12; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($i === (int)date('n')) {
                                                    echo 'selected';
                                                  } ?>>
                  <?php echo $i . '月'; ?></option><?php } ?>
              <?php if (!empty($geteditstudy['$study_month'])) { ?>
                <option value="<?php echo $geteditstudy['$study_month'] ?>" <?php if ($i == $geteditstudy['$study_month']) {
                                                                              echo 'selected';
                                                                            } ?>>
                  <?php echo $geteditstudy['$study_month'] ?></option>
              <?php } ?>
            </select>
            <select class="day-list" name="day" id="">
              <?php for ($i = 1; $i <= 31; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($i == substr($geteditstudy['study_date'], -2)) {
                                                    echo 'selected';
                                                  } ?>>
                  <?php echo $i . '日'; ?>
                </option>
              <?php } ?>
            </select>
          </div>
        </section>
        <section class="time">
          <div class="selectbox">
            <h1> 時間を選択</h1>
            <div class="err_msg"><?php if (!empty($err_msg['time'])) echo $err_msg['time']; ?></div>
            <select name="time-list">
              <option value="0">選択してください</option>
              <?php for ($i = 15; $i <= 90; $i += 15) { ?>
                <option value="<?php echo $i; ?>" <?php if ($i == $geteditstudy['study_time']) {
                                                    echo 'selected';
                                                  } ?>>
                  <?php echo $i . '分'; ?>
                <?php } ?>
                </option>

            </select>
          </div>
        </section>

        <section class="category">
          <div class="selectbox">
            <h1>カテゴリを選択　<span class="add_Category"><a href="Edit_Category.php">カテゴリの追加はこちら</a></span></h1>
            <div class="err_msg"><?php if (!empty($err_msg['category'])) echo $err_msg['category']; ?></div>
            <select name="category-list">
              <option value="0">選択してください</option>
              <?php
              foreach ($getcategory as $key => $val) {
              ?>
                <option value="<?php echo $val['category_name'] ?>"><?php echo $val['category_name'] ?></option><?php } ?>
              <?php if (!empty($geteditstudy['study_category'])) { ?>
                <option value="<?php echo $geteditstudy['study_category'] ?>" <?php if (!empty($geteditstudy['study_category'])) echo 'selected'; ?>><?php echo $geteditstudy['study_category'] ?></option>
              <?php } ?>
            </select>
          </div>
        </section>

        <section class="detail">
          　　　<h1>内容を記入</h1>
          <div class="err_msg"><?php if (!empty($err_msg['detail'])) echo $err_msg['detail']; ?></div>
          <textarea name="study-detail" id="" cols="40" rows="7" placeholder="内容"><?php if (!empty($geteditstudy['study_detail'])) echo $geteditstudy['study_detail'] ?></textarea>
        </section>
        <div class='btn-container'>
          <input type="submit" value="再登録">
        </div>
      </form>

    </div>

    <a class="i_jump" href="ReadBack.php">確認ページへ戻る</a>
  </main>
</body>
<style>
  main {
    background-color: #ddd;
    height: 1400px;
    width: 100%;
  }

  form {
    margin: 0 auto;
    padding: 5px;
    width: 400px;
    border: 5px solid rgb(0, 0, 0, 0);
    display: block;
    height: 550px;
  }


  textarea[name="study-detail"] {
    font-size: 16px;
    margin: 10px 1px;
  }


  .detail p {
    margin: 0;
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
    margin-bottom: 0px;
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

  .page-title {
    margin-bottom: 50px;

    text-align: center;
    font-size: 35px;
    font-weight: bold;
    letter-spacing: 5px;
  }

  .site-width2 {
    margin: 0 auto;
    width: 980px;

    padding-top: 10px;
    padding-bottom: 110px;
  }

  .add_Category a {
    font-size: 13px;
    color: #333;
  }

  .year-list,
  .month-list,
  .day-list {
    width: 117px;
    margin-right: 10px;
    float: left;
  }
</style>