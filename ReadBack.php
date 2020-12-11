<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「学習振り返りページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
$_SESSION['file'] =  basename(__FILE__);
$user_id = $_SESSION['user_id'];

$serchyear = (!empty($_GET['year'])) ? $_GET['year'] : '';
$serchyear2 = (!empty($_GET['year2'])) ? $_GET['year2'] : '';
$serchmonth = (!empty($_GET['month'])) ? $_GET['month'] : '';
$serchmonth2 = (!empty($_GET['month2'])) ? $_GET['month2'] : '';
$serchday = (!empty($_GET['day'])) ? $_GET['day'] : '';
$serchday2 = (!empty($_GET['day2'])) ? $_GET['day2'] : '';
$serchcategory = (!empty($_GET['category'])) ? $_GET['category'] : '';

$user = getuser($user_id);
$startdate = date('Y-m-d', strtotime($user['create_date']));
$start_month = date('m', strtotime($user['create_date']));
$start_day = date('d', strtotime($user['create_date']));

$_SESSION['$serchyear'] = $serchyear;
$_SESSION['$serchyear2'] = $serchyear2;
$_SESSION['$serchmonth'] = $serchmonth;
$_SESSION['$serchmonth2'] = $serchmonth2;
$_SESSION['$serchday'] = $serchday;
$_SESSION['$serchday2'] = $serchday2;
$_SESSION['category'] = $serchcategory;

$from_date = (!empty($_GET['year'])) ? $serchyear . '-' . sprintf('%02d', $serchmonth) . '-' . sprintf('%02d', $serchday) : '2010-01-01';


$to_date = (!empty($_GET['year2'])) ? $serchyear2 . '-' . sprintf('%02d', $serchmonth2) . '-' . sprintf('%02d', $serchday2) : date('Y-m-d');
$includecategory = (!empty($_GET['category'])) ? "'" . $serchcategory . "'" : '';;

if (!empty($_SESSION['get_month'])) {
  $month = $_SESSION['get_month'];
  $from_date = 2020 . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', 1);
  $to_date = 2020 . '-' . sprintf('%02d', $month) . '-' . 31;
  unset($_SESSION['get_month']);
}
$getstudy = getstudy($user_id, $from_date, $to_date, $includecategory);

$getcategory = getcategory();

$getstudytime = getstudytime($user_id, $from_date, $to_date, $includecategory);
debug('getsutdytime' . print_r($getstudytime, true));
$edit_study = (!empty($_GET['study_id'])) ? $_GET['study_id'] : 'データなし';
if (!empty($_GET['study_id'])) {
  $_SESSION['Edit_study_id'] = $edit_study;
  header('Location:Edit_study.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>学習確認ページ</title>
</head>

<body>

  <?php
  require('header.php');
  require('auth.php');
  ?>

  <main>
    <div class="site-width">
      <div class="page-title">
        <p><img class='icon' src="img/book.png" alt="">学習の振り返り</p>
      </div>
      <section>

        <div>
          <div class="search-msg">
            【<?php if (!empty($from_date) & $from_date != '2010-01-01' or !empty($to_date) & $to_date != date('Y-m-d')) {
                echo $from_date . '~' . $to_date . 'の検索結果';
              } else {
                echo '全ての期間の学習内容を表示中';
              }
              ?>】 <h2>学習時間合計　<span class='time'><?php echo round($getstudytime['sum(study_time)'] / 60, 1); ?></span>h</h2>
          </div>
        </div>
        <form class='search' method="get">
          <h1>◉検索する</h1>

          <div class='keyword-box'>
            <div class="from">
              <p>from</p>
              <select class="year-list" name="year" id="">
                <option value="<?php echo date('Y'); ?>"><?php echo date('Y') . '年'; ?></option>
                <?php for ($i = date('Y') - 1; $i >= 2010; $i--) { ?>
                  <option value="<?php echo $i; ?>"><?php echo $i . '年'; ?></option><?php } ?>

                <!-- 『年』初期値の設定 -->
                <?php if (!empty($_SESSION['$serchyear'])) { ?>
                  <option value="<?php echo $_SESSION['$serchyear'] ?>" <?php if (!empty($_SESSION['$serchyear'])) echo 'selected'; ?>>
                    <?php echo $_SESSION['$serchyear'] ?></option>
                <?php } ?>

              </select>
              <select class="month-list" name="month">

                <?php for ($i = 1; $i <= 12; $i++) { ?>
                  <option value="<?php echo $i; ?>" <?php if ($i === (int)$start_month) {
                                                      echo 'selected';
                                                    } ?>>
                    <?php echo $i . '月'; ?></option><?php } ?>
                <?php if (!empty($_SESSION['$serchmonth'])) { ?>

                  <!-- 『月』初期値の設定 -->
                  <option value="<?php echo $_SESSION['$serchmonth'] ?>" <?php if (!empty($_SESSION['$serchmonth'])) echo 'selected'; ?>>
                    <?php echo $_SESSION['$serchmonth'] ?></option>
                <?php } ?>
              </select>

              <select class="day-list" name="day" id="">
                <?php for ($i = 1; $i <= 31; $i++) { ?>
                  <option value="<?php echo $i; ?>" <?php if ($i === (int)$start_day) {
                                                      echo 'selected';
                                                    } ?>>
                    <?php echo $i . '日'; ?>
                  </option>
                <?php } ?>

                <?php if (!empty($_SESSION['$serchday'])) { ?>
                  <option value="<?php echo $_SESSION['$serchday'] ?>" <?php if (!empty($_SESSION['$serchday'])) echo 'selected'; ?>>
                    <?php echo $_SESSION['$serchday'] ?></option>
                <?php } ?>
              </select>
            </div>
            <span class='while'>~</span>
            <div class="to">
              <p>to</p>
              <select class="year-list" name="year2" id="">
                <option value="<?php echo date('Y'); ?>"><?php echo date('Y') . '年'; ?></option>
                <?php for ($i = date('Y') - 1; $i >= 2010; $i--) { ?>
                  <option value="<?php echo $i; ?>"><?php echo $i . '年'; ?></option><?php } ?>
                <?php if (!empty($_SESSION['$serchyear2'])) { ?>
                  <option value="<?php echo $_SESSION['$serchyear2'] ?>" <?php if (!empty($_SESSION['$serchyear2'])) echo 'selected'; ?>>
                    <?php echo $_SESSION['$serchyear2'] ?></option>
                <?php } ?>
              </select>
              <select class="month-list" name="month2">
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                  <option value="<?php echo $i; ?>" <?php if ($i === (int)date('n')) {
                                                      echo 'selected';
                                                    } ?>>
                    <?php echo $i . '月'; ?>
                  </option>
                <?php } ?>
                <?php if (!empty($_SESSION['$serchmonth2'])) { ?>
                  <option value="<?php echo $_SESSION['$serchmonth2'] ?>" <?php if (!empty($_SESSION['$serchmonth2'])) echo 'selected'; ?>>
                    <?php echo $_SESSION['$serchmonth2'] ?></option>
                <?php } ?>
              </select>
              <select class="day-list" name="day2" id="">
                <?php for ($i = 1; $i <= 31; $i++) { ?>
                  <option value="<?php echo $i; ?>" <?php if ($i === (int)date('d')) {
                                                      echo 'selected';
                                                    } ?>>
                    <?php echo $i . '日'; ?>
                  </option>
                <?php } ?>
                <?php if (!empty($_SESSION['$serchday2'])) { ?>
                  <option value="<?php echo $_SESSION['$serchday2'] ?>" <?php if (!empty($_SESSION['$serchday2'])) echo 'selected'; ?>>
                    <?php echo $_SESSION['$serchday2'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="category">
              <p>category</p>
              <select class="category-list" name="category" id="">
                <option value="0">カテゴリで絞る</option>
                <?php
                foreach ($getcategory as $key => $val) { ?>
                  <option value="<?php echo $val['category_name'] ?>">
                    <?php echo $val['category_name'] ?>
                  </option><?php  } ?>
                <?php if (!empty($_SESSION['category'])) { ?>
                  <option value="<?php echo $_SESSION['category'] ?>" <?php if (!empty($_SESSION['category'])) echo 'selected'; ?>><?php echo $_SESSION['category'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <input id='searchbtn' type="submit" value="検索">
        </form>
        <table>
          <?php if (empty($getstudy)) {
          ?>
            <div class="err_msg">
              データがありません
              <?php if ($from_date > $to_date) { ?>
                <p>検索日付を確認してください</p>
              <?php } ?>
            </div>

          <?php } else { ?>
            <thead>
              <tr>
                <th class="size_s">日付</th>
                <th class='size_m'>カテゴリ</th>
                <th class="size_s">時間(分)</th>
                <th class="size_l">内容</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($getstudy as $key => $val) { ?>
                <tr>
                  <td class="size_s"><?php echo $val['study_date']; ?></td>
                  <td class='size_m'><?php echo $val['study_category']; ?></td>
                  <td class="size_s"><?php echo $val['study_time']; ?></td>
                  <td class="size_l"><?php echo $val['study_detail']; ?></td>
                  <form method="get">
                    <td class="size_s">
                      <textarea name="study_id" id="study_id"><?php echo $val['id']; ?></textarea>
                      <input class='editbtn' type="submit" value="編集">
                  </form>
                  </td>

                </tr>
              <?php } ?>


            </tbody>
          <?php } ?>
        </table>
      </section>
      <a class="i_jump" href="index.php">HOMEへ戻る</a>
    </div>
    <?php require('footer.php'); ?>
    <style>
      #study_id {
        display: none;
      }

      table {
        margin-top: 50px;
        margin-bottom: 50px;
      }

      thead {
        display: block;
      }

      tbody {
        overflow-y: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        display: block;
        width: 1080px;
        height: 400px;
        box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .5);
      }

      .err_msg {
        margin: 80px 0;
        text-align: center;
        width: 100%;
        font-size: 30px;
        display: block;
      }

      .search-msg {
        margin: 0 auto;
        width: 70%;
        font-size: 30px;
      }

      .search-msg h2 {
        margin: 0 auto;
        width: 400px;
        font-size: 30px;
        display: block;
      }

      .time {
        font-size: 50px;
      }

      .edit {
        float: right;
      }

      main {
        background-color: #ddd;
        height: 1100px;
      }

      .table-title {
        text-align: center;
        padding-bottom: 20px;
        font-size: 25px;
      }

      td {
        background-color: #fff;
        height: 40px;
      }

      .size_s {
        width: 80px;
        text-align: center;
      }

      .size_m {
        width: 130px;
      }

      .size_l {
        width: 700px;
      }

      .search {
        margin: 0 auto;
        padding: 15px;
        width: 800px;
        height: 90px;
        border: 5px solid rgb(0, 0, 0, 0);
        display: block;
        color: aliceblue;
        margin-top: 20px;
        margin-bottom: 20px;
        background: #333;
        box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .5);
      }

      .from p,
      .to p,
      .category p {
        display: block;
        width: 100px;
        margin: 5px;
      }

      .category p {
        margin-left: 23px;
      }

      .from,
      .to,
      .category {
        float: left;
      }

      select {
        width: 144px;
        padding-left: 6px;
        float: left;
        margin: 0;
        height: 35px;
        font-size: 14px;
        border: 1px solid #eee;
        margin-right: 15px;
      }

      .while {
        display: block;
        float: left;
        margin-right: 14px;
        margin-top: 27px;
        font-size: 28px;
      }

      .year-list {
        width: 75px;
      }

      .month-list,
      .day-list {
        width: 56px;
      }

      form h1 {
        font-size: 16px;
        margin: -10px;
        margin-bottom: 0px;
        display: block;
        width: 90px;
      }

      .category-list {
        margin-left: 23px;
      }


      #searchbtn {
        height: 35px;
        margin-left: 15px;
        margin-top: 33px;
        width: 73px;
        border: none;
        background: #FF773E;
        color: white;
        font-size: 14px;
        cursor: pointer;
      }

      .keyword-box {
        margin-left: 31px;
      }
    </style>
  </main>