<?php
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「学習振り返りページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

$getagtstudy = getagtstudy($user_id);
debug('$getagtstudy:' . print_r($getagtstudy, true));
$user = getuser($user_id);


$includecategory = '';
$startdate = date('Y-m-d', strtotime($user['create_date']));
$start_month = date('m', strtotime($user['create_date']));

$getstudytime = getstudytime($user_id, $startdate, date('Y-m-d'), $includecategory);
$sutdy_period = ((strtotime(date('Y-m-d')) - strtotime($startdate)) / 86400);
$sutdy_startlastday = 2020 . '-' . sprintf('%02d', $start_month) . '-' . 31;
$sutdy_startfirstmonthdays = ((strtotime($sutdy_startlastday) - strtotime($startdate)) / 86400);


debug(' $startdate' . print_r($startdate, true));
debug(' $sutdy_startlastday' . print_r($sutdy_startlastday, true));


$getstudy = getstudy($user_id, date('Y-m-d'), date('Y-m-d'), $includecategory);

$gettime1 = getstudytime($user_id, date('Y-m-d'), date('Y-m-d'), $includecategory);
$gettime2 = array_shift($gettime1);
$todaystudytime = round($gettime2 / 60, 1);
if (!empty($_GET['month_id'])) {
  $_SESSION['get_month'] = $_GET['month_id'];
  header('location:ReadBack.php');
}
?>
<div class="study_detail">
  <div class="site-width">
    <div class="page-title">
    </div>
    <section class="today_list">
      <div class="table-title">
        <h2><img class='icon' src="img/book.png" alt=""><span>本日<span><?php echo date('m/d'); ?></span>の学習履歴 　(計<span class="today_time"><?php echo $todaystudytime; ?></span>h)</span>

      </div>
      <table>
        <thead>
          <tr>
            <th class="size_sss">日付</th>
            <th class='size_m'>カテゴリ</th>
            <th class="size_s">時間(分)</th>
            <th class="size_l">内容</th>
          </tr>
        </thead>
        <tbody class="alltbody">
          <?php foreach ($getstudy as $key => $val) { ?>
            <tr>

              <td class="size_ss"><?php echo substr($val['create_date'], 0, 16); ?></td>
              <td class='size_m'><?php echo $val['study_category']; ?></td>
              <td class="size_s"><?php echo $val['study_time']; ?></td>
              <td class="size_l"><?php if (!empty($getstudy)) {
                                    echo $val['study_detail'];
                                  } else {
                                    echo '本日は学習を行っておりません';
                                  } ?></td>
              <form method="get">
                <td class="size_s">
                  <textarea name="study_id" id="study_id"><?php echo $val['id']; ?></textarea>
                  <input class='editbtn' type="submit" value="編集">
              </form>
            </tr>
          <?php } ?>



        </tbody>

      </table>
    </section>
  </div>
  <div class="total-study">
    <div class="table-title">
      <h2><img class='icon' src="img/time.png" alt="">累計学習時間</h2>
    </div>
    <section class="data-list">
      <div class="data">
        <ul class="data-ul">
          <?php foreach ($getagtstudy as $key => $val) {
            if ($val['study_month'] == $start_month) {
              $study_days = $sutdy_startfirstmonthdays;
            } else {
              $study_days = 30;
            }  ?>
            <li class="data_month">
              <div>
                <h1 class="data-title"><img class='icom' src="img/book.png" alt=""><?php echo (int)$val['study_month']; ?>月
                  <form class='month-search' 　method="get">
                    <textarea name="month_id" id="study_id"><?php echo (int)$val['study_month']; ?></textarea>
                    <input class='month-btn' type="submit" value="詳細 ">>
                  </form>
                </h1>

                <p class="study-time">合計時間：<span class="sum"> <?php echo round(($val['sum_time'] / 60), 1); ?></span>h </p>
                <p class="study-time">1日平均：<span class="sum"><?php echo round(($val['sum_time'] / 60) / $study_days, 1);
                                                              ?></span>h</p>
              </div>
            </li>
          <?php } ?>
        </ul>

      </div>
      <div class="data_space">
        <h1 class="data-title"><img class='icom' src="img/book.png" alt="">学習情報
        </h1>
        <p>学習開始日：<?php echo date('m-d', strtotime($user['create_date'])); ?></p>
        <p>開始から　：<span><?php echo $sutdy_period; ?></span>日</p>
        <p>総学習時間：<span><?php echo round(($getstudytime['sum(study_time)'] / 60), 1);  ?></span>h</p>
        <p>平均　　　：<span><?php echo round(($getstudytime['sum(study_time)'] / $sutdy_period) / 60, 1); ?>h</p>
      </div>
    </section>
  </div>
</div>
<style>
  .month-search {
    float: right;
    left: 20px;
    text-decoration: none;
    font-size: 22px;
  }

  .month-btn {
    background-color: #fff;
    border: none;
    color: #0000FF;
  }

  .icom {
    height: 22px;
    width: 22px;
    margin-right: 7px;
  }

  #study_id {
    display: none;
  }

  td a {
    font-size: 15px;
  }

  .data-list {
    margin-top: -30px;
  }

  .today_list {
    height: 450px;

  }

  .table-title h1 {
    font-size: 40px;

  }

  .total-study {
    background-color: #ddd;
    height: 553px;
    margin: 0 auto;
    width: 980px;
    padding-bottom: 10px;
    padding-top: 10px;

  }

  thead {
    display: block;
  }

  .study_detail {
    height: 471px;
    background-color: #fff;
  }

  .alltbody {
    overflow-y: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    display: block;
    width: 1080px;
    height: 270px;
    box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .5);
  }



  .startdate {
    font-size: 21px;
    width: 95px;
    float: right;
    display: block;
    margin: 0px;
    margin-top: -9px;
  }

  .sum {
    font-size: 40px;
  }

  .avg {
    font-size: 30px;
  }

  .table-title {
    text-align: center;
    font-size: 25px;
  }

  td {
    color: #333;
    height: 40px;
    background-color: #DDDDDD;
  }

  .size_s {
    width: 80px;
    text-align: center;
  }

  .size_ss {
    width: 140px;
    text-align: center;
    font-size: 14px;
  }

  .size_sss {
    width: 140px;
    text-align: center;

  }

  .size_m {
    width: 130px;
  }

  .size_l {
    width: 700px;
  }

  .data {
    width: 600px;
    margin: 10px;
    float: left;
  }

  .data_space {
    height: 300px;
    width: 300px;
    margin: 10px;
    background-color: #FFF;
    float: right;
    margin-top: 56px;
    font-size: 30px;
    box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .5);
  }

  .data_space p {
    font-size: 28px;
    margin: 0 auto;
    margin-left: 25px;
    margin-top: 5px;
  }

  .data_space h1 {
    text-align: center;
  }

  .data_month {
    height: 270px;
    width: 300px;
    margin-top: 55px;
    background-color: #FFF;
    color: #333;
    display: inline-block;
    margin-top: 30px;
    margin-right: 30px;
    box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .5);
  }



  .data-ul {
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    width: 600px;
    padding: 0;
    text-align: center;
    height: 330px;
  }

  .data-title {
    padding: 6px;
    font-size: 25px;
    border-bottom: 2px solid #ddd;
    margin: 0 auto;

    margin-bottom: 25px;

  }

  .data-title2 {
    font-size: 30px;
    margin: 0;
  }

  .study-time {
    font-size: 25px;
    margin: 0;
  }
</style>