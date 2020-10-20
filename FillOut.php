<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('学習記録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
$_SESSION['file'] =  basename(__FILE__);
$_SESSION['category'] = '';
$study_time = (!empty($_POST['time-list'])) ? $_POST['time-list'] : '';
$study_category = (!empty($_POST['category-list'])) ? $_POST['category-list'] : '';
$study_detail = (!empty($_POST['study-detail'])) ? $_POST['study-detail'] : '';

$_SESSION['study_time'] = $study_time;
$_SESSION['study_category'] = $study_category;
$_SESSION['study_detail'] = $study_detail;

$user= getuser($_SESSION['user_id']);
$getcategory = getcategory();


if (!empty($_POST)) {
  debug('POST送信があります');
  vaildRequired($study_time, 'time');
  vaildRequired($study_category, 'category');

  if (empty($err_msg)) {
    debug('バリデーションOK');
  try {

    $dbh = dbConnect();
    $sql = 'INSERT INTO `study_detail`(user_id,study_time,study_category,study_detail,study_year,study_month,study_date,create_date) 
      VALUES(:user_id,:study_time,:study_category,:study_detail,:study_year,:study_month,:study_date,:create_date)';

    $data = array(
      ':user_id' => $_SESSION['user_id'],
      ':study_time' => $study_time,
      ':study_category' => $study_category,
      ':study_detail' => $study_detail,
      ':study_year' => date('Y'),
      ':study_month' => date('m'),
      ':study_date' => date('Y-m-d'),
      ':create_date' => date('Y-m-d-H-i',strtotime("-15 hour"))
    );
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      debug('{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{[');
      $_SESSION['msg_succes'] = MSG11;
      debug('マイページへ遷移します。');
      session_write_close();
      header("Location:index.php");
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}}
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
  require('header.php'); ?>
  <?php
  require('auth.php');
  ?>

  <main>
    <div class="site-width">
      <div class="page-title">
        <p class="page-title"><img class ='icon' src="img/pen.png" alt="">学習を記録する</p>
      </div>
      <form action="" method="post">
        <section class="time">
          <div class="selectbox">
            <p>時間を選択</p>
            <div class="err_msg"><?php if (!empty($err_msg['time'])) echo $err_msg['time']; ?></div>
            <select name="time-list">
              <option value="0">選択してください</option>
               <?php for($i=15; $i <= 90 ; $i+=15)  {?>
              <option value="<?php echo $i;?>">
               <?php echo $i.'分';?>
               <?php } ?>
               </option>
               <?php if(!empty($_SESSION['study_time'])) {?>
              <option value="<?php echo $_SESSION['study_time']?>" <?php if(!empty($_SESSION['study_time']))echo 'selected';?>><?php echo $_SESSION['study_time']?>
              </option>
               <?php } ?>
            </select>
          </div>
        </section>

        <section class="category">
          <div class="selectbox">
            <p>カテゴリを選択　<span class="add_Category"><a href="Edit_Category.php">カテゴリの追加はこちら</a></span></p>
            <div class="err_msg"><?php if (!empty($err_msg['category'])) echo $err_msg['category']; ?></div>
            <select name="category-list">
              <option value="0">選択してください</option>
              <?php
              foreach($getcategory as $key => $val){
              ?>
              <option value="<?php echo $val['category_name']?>"><?php echo $val['category_name']?></option><?php  } ?>
              <?php if(!empty($_SESSION['study_category'])) {?>
              <option value="<?php echo $_SESSION['study_category']?>" <?php if(!empty($_SESSION['study_category']))echo 'selected';?>><?php echo $_SESSION['study_category']?></option>
              <?php } ?>
            </select>
          </div>
        </section>

        <section class="detail">
          　　　<p>内容を記入</p>
          <div class="err_msg"><?php if (!empty($err_msg['detail'])) echo $err_msg['detail']; ?></div>
          <textarea name="study-detail" id="" cols="40" rows="7" placeholder="内容"><?php if(!empty($_SESSION['study_detail'])) echo $_SESSION['study_detail']?></textarea>
        </section>
        <div class='btn-container'>
          <input  id='Registration'  type="submit" value="登録">
        </div>
      </form>

    </div>
    <?php
    require('StudyDetail_use.php');
    ?>
    <a class="i_jump" href="index.php">HOMEへ戻る</a>
  </main>
  <?php require('footer.php'); ?>
</body>
</html>
<style>
  main {
    background-color: #ddd;
    height: 1100px;
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
  }

  .detail {
    position: absolute;
    top: 516px;
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

  #Registration {
    margin: 15px 125px;
    padding: 15px 30px;
    width: 150px;
    border: none;
    background: #FF773E;
    color: white;
    font-size: 14px;
    cursor: pointer;
    position: absolute;
    top: 740px;
  }
 
  .page-title {
    margin-bottom: 10px;

    text-align: center;
    font-size: 35px;
    font-weight: bold;
    letter-spacing: 5px;
  }

  .site-width{
    margin: 0 auto;
    width: 980px;
    padding-bottom: 10px;
    padding-top: 10px;
  }

  .add_Category a {
    font-size: 13px;
    color: #333;
  }
  .err_msg{
  color: red;
  float: left;
  margin-top: 5px ;
  margin-left: 10px;
}
section p{

  margin-top: 5px ;
  float: left;
}
.detail p{

margin-top: 5px ;
float: left;
width:540px;
}

</style>