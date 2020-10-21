<?php

if (!empty($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}
require('auth.php');
$edit_study = (!empty($_GET['study_id'])) ? $_GET['study_id'] : 'データなし';
if (!empty($_GET['study_id'])) {
  header('Location:Edit_study.php');
  exit();
  $_SESSION['Edit_study_id'] = $edit_study;
}
?>
<style>
  a {
    text-decoration: none;
    color: #333;
  }

  img {
    height: 10px;
    width: 10px;
  }

  .second_header {
    width: 100%;
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    height: 70px;

    margin: 0px;
  }

  .contents {
    height: 70px;
    float: left;
    text-align: center;
    border-left: 1px solid #ddd;
  }

  #contents-last {
    border-right: 1px solid #ddd;
  }

  .contents p {
    margin: 0;
  }

  .top-nav {
    height: 70px;
    width: 605px;
    margin: 0 auto;

  }

  .top-nav a {
    text-decoration: none;
    color: #333;
    display: block;
    padding: 1px 0px;
    width: 150px;
  }

  .stauts-user span {
    float: left;
  }

  .stauts-user p {
    margin: 0;
    float: left;
    margin-left: 10px;
  }

  .logout {
    float: right;
    display: block;
    background-color: #555;
    width: 100px;
  }

  .logout a {
    color: #fff;
    margin-left: 10px;
  }

  .stauts {
    list-style: none;
    width: 55%;
    margin: 0 auto;
    height: 34px;
    margin-top: 10px;
  }

  .contentsmark {
    height: 70px;
    float: left;
    text-align: center;
    border-left: 1px solid #ddd;
    background-color: #333;
  }

  .contentsmark p {
    color: #fff;
    margin: 0;
  }


  .msg-slide {
    position: absolute;
    top: 200px;
    text-align: center;
    background: rgba(22, 96, 330, 0.9);
    width: 100%;
    height: 100px;
    padding: 60px;
    text-align: center;
    font-size: 54px;
    line-height: 40px;
    color: #fff;
    margin-top: 20px;
  }

  .icon {
    height: 30px;
    width: 30px;
    margin-top: 7px;
  }

  #last {
    border-right: 1px solid #ddd;
  }

  .main_icon {
    width: 30px;
    height: 30px;
    margin-right: 7px;
    float: left;
    margin-left: 30px;
    margin-top: 9px;
  }
</style>
<header>
  <div>
    <div class="first_header">

      <a href="TopPage.php"> <img class='main_icon' src="img/mainicon.png" alt="">
        <h1> 学習日誌くん</h1>
      </a>
    </div>
    <div class="second_header">
      <nav class="top-nav">
        <div class="<?php if ($_SESSION['file'] === 'index.php') {
                      echo 'contentsmark';
                    } else {
                      echo 'contents';
                    } ?>">
          <img class='icon' src="img/home.png" alt="">
          <a href="index.php">
            <p>HOME</p>
          </a>
        </div>
        <div class="<?php if ($_SESSION['file'] === 'FillOut.php') {
                      echo 'contentsmark';
                    } else {
                      echo 'contents';
                    } ?>">
          <img class='icon' src="img/pen.png" alt="">
          <a href="FillOut.php">
            <p>学習を記録する</p>
          </a>
        </div>
        <div class="<?php if ($_SESSION['file'] === 'ReadBack.php') {
                      echo 'contentsmark';
                    } else {
                      echo 'contents';
                    } ?>">
          <img class='icon' src="img/book.png" alt="">
          <a href="ReadBack.php">
            <p>学習を振り返る</p>
          </a>
        </div>
        <div id='last' class="<?php if ($_SESSION['file'] === 'setting.php') {
                                echo 'contentsmark';
                              } else {
                                echo 'contents';
                              } ?>">
          <img class='icon' src="img/setting.png" alt="">
          <a href="setting.php">
            <p>その他設定</p>
          </a>
        </div>

    </div>
    </nav>
  </div>
  <ul class="stauts">
    <li class="stauts-user">
      <span><img src="img/user_icon.jpeg" alt=""></span>
      <p><?php echo getusername($user_id); ?></p>
    </li>
    <li class="logout">
      <a href="logout.php">ログアウト</a>
    </li>
  </ul>
</header>
<div class='animate_msg'>
  <p id="js-show-msg" style="display:none;" class="msg-slide"><?php echo getSessionFlash('msg_succes'); ?></p>
</div>