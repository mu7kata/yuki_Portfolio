<?php
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「学習振り返りページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

$getagtstudy = getagtstudy($user_id);
$user = getuser($user_id);

$includecategory = '';
$startdate = date('Y-m-d', strtotime($user['create_date']));
$getstudytime = getstudytime($user_id, $startdate, date('Y-m-d'), $includecategory);
$sutdy_period = ((strtotime(date('Y-m-d')) - strtotime($startdate)) / 86400);
$getstudy = getstudy($user_id, date('Y-m-d'), date('Y-m-d'), $includecategory);
$gettime1 = getstudytime($user_id, date('Y-m-d'), date('Y-m-d'), $includecategory);
$gettime2 = array_shift($gettime1);
$todaystudytime = round($gettime2 / 60, 1);
?>
<div class="study_detail">
  <div class="site-width">
    <div class="page-title">
    </div>
    <section class="today_list">
      <div class="table-title">
        <h2><span>本日<span><?php echo date('m/d'); ?></span>の学習履歴　(計<span class="today_time"><?php echo $todaystudytime; ?></span>h)</span>
      </div>
      <table>
        <thead>
          <tr>
            <th class="size_s">日付</th>
            <th class='size_m'>カテゴリ</th>
            <th class="size_s">時間(分)</th>
            <th class="size_l">内容</th>
          </tr>
        </thead>
        <tbody class="alltbody">
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
            </tr>
          <?php } ?>


        </tbody>

      </table>
    </section>
  </div>
</div>
<style>
  td a {
    font-size: 15px;
  }

  #study_id {
    display: none;
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
    margin-bottom: 80px;
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
    font-size: 50px;
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

  .size_m {
    width: 130px;
  }

  .size_l {
    width: 700px;
  }
</style>