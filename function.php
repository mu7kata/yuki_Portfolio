<?php

ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

$debug_flg = true;

function debug($str)
{
  global $debug_flg;
  if (!empty($debug_flg)) {
    error_log('デバッグ：' . $str);
  }
}
session_save_path("/var/tmp/");
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
ini_set('session.cookie_lifetime ', 60 * 60 * 24 * 30);
session_start();
session_regenerate_id();

function debugLogStart()
{
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID：' . session_id());
  debug('セッション変数の中身：' . print_r($_SESSION, true));
  debug('現在日時タイムスタンプ：' . time());
  if (!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])) {
    debug('ログイン期限日時タイムスタンプ：' . ($_SESSION['login_date'] + $_SESSION['login_limit']));
  }
}

define('MSG01', '入力必須です');
define('MSG02', 'emailの形式で入力してください');
define('MSG03', 'パスワード（再入力）が合っていません');
define('MSG04', '6文字以上で入力してください');
define('MSG05', '256文字以内で入力してください');
define('MSG06', '半角英数字のみご利用いただけます');
define('MSG07', 'エラーが発生しました。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', '文字で入力してください');
define('MSG10', 'パスワードが間違っています');
define('MSG11', '記録が完了しました');
define('MSG12', 'カテゴリを編集しました');
define('MSG13', '学習内容を変更しました');


$err_msg = array();

function vaildRequired($str, $key)
{
  if ($str === '') {
    global $err_msg;
    $err_msg[$key] = MSG01;
    debug('入力されていません');
  }
}


function vaildEmail($str, $key)
{
  if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
function dbConnect()
{

  $dsn = 'mysql:dbname=heroku_4e17269070abffd;host=us-cdbr-east-02.cleardb.com;charset=utf8';
  $user = 'b2cfd88da643c1';
  $password = '71005a34';
  $option = array(

   

    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );

  $dbh = new PDO($dsn, $user, $password, $option);
  return $dbh;
}

// email重複チェック
function vaiildDup($field, $str)
{
  global $err_msg;

  try {
    $dbh  = dbConnect();
    $sql = 'SELECT * FROM users WHERE :field = :st AND delete_flg = 0';
    $data = array(':st' => $str, ':field' => $field);
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty(array_shift($result))) {
      $err_msg['email'] = MSG08;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}

// 同値チェック
function validMatch($str, $str2, $key)
{
  if ($str !== $str2) {
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}

//最小文字数チェック（６文字
function validMinLen($str, $key, $min = 6)
{
  if (mb_strlen($str) < $min) {
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}

// 最大文字数チェック（２５６文字）
function validMaxLen($str, $key, $max = 256)
{
  if (mb_strlen($str) > $max) {
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
// 半角チェック
function validHalf($str, $key)
{

  if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}


// パスワードチェック　
function validpass($str, $key)
{
  validHalf($str, $key);
  validMaxLen($str, $key);
  validMinLen($str, $key);
}

// エラーメッセージ表示
function getErrMsg($key)
{
  if (!empty($_POST[$key])) {
    return $_POST[$key];
  }
}

function queryPost($dbh, $sql, $data)
{
  debug('DBへの命令結果を反映');
  $stmt = $dbh->prepare($sql);

  if (!$stmt->execute($data)) {
    debug('クエリに失敗しました。');
    debug('失敗したSQL：' . print_r($stmt, true));
    $err_msg['common'] = MSG07;
    return 0;
  }
  debug('クエリ成功');
  return $stmt;
}
function getuser($user_id)
{
  debug('ユーザー情報を取得します。');
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM users WHERE id = :user_id AND delete_flg=0';
    $data = array(':user_id' => $user_id);
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function getusername($user_id)
{
  debug('ユーザー名を取得します。');
  global $err_msg;

  try {
    $dbh  = dbConnect();
    $sql = 'SELECT user_name FROM users WHERE id = :user_id AND delete_flg = 0';
    $data = array(':user_id' => $user_id);
    $stmt = queryPost($dbh, $sql, $data);


    if ($stmt) {
      return  $stmt->fetch(PDO::FETCH_COLUMN);
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
function getcategory()
{
  debug('カテゴリを取得します');
  try {
    $dbh = dbConnect();
    $sql = 'SELECT category_name FROM category WHERE user_id = :user_id';
    $data = array(':user_id' => $_SESSION['user_id']);
    $stmt = queryPost($dbh, $sql, $data);
    if ($stmt) {
      return $stmt->fetchAll();
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}


function getstudy($user_id, $from_date, $to_date, $includecategory)
{
  debug('学習内容の取得');
  $from_date = date('Y-m-d', strtotime($from_date));
  $to_date = date('Y-m-d', strtotime($to_date));

  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM study_detail WHERE user_id = :user_id  AND study_date  BETWEEN :from_date and :to_date';

    if (!empty($includecategory)) $sql .= ' AND study_category  = ' . $includecategory;
    $data = array(':user_id' => $user_id, ':from_date' => $from_date, ':to_date' => $to_date);



    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      debug('成功');
      return $stmt->fetchAll();
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function geteditstudy($user_id, $study_id)
{
  debug('編集する学習内容の取得');

  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM study_detail WHERE user_id = :user_id  AND id=:study_id';

    $data = array(':user_id' => $user_id, ':study_id' => $study_id);
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      debug('成功');
      return $stmt->fetch();
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function getstudytime($user_id, $from_date, $to_date, $includecategory)
{
  try {
    $dbh = dbConnect();
    $sql = 'SELECT sum(study_time) FROM study_detail WHERE user_id = :user_id  AND study_date  BETWEEN :from_date and :to_date';

    if (!empty($includecategory)) $sql .= ' AND study_category  = ' . $includecategory;
    $data = array(':user_id' => $user_id, ':from_date' => $from_date, ':to_date' => $to_date);

    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      return $stmt->fetch();
    } else {
      debug('失敗');
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function getagtstudy($user_id)
{
  debug('月毎の学習時間の取得');

  try {
    $dbh = dbConnect();
    $sql = 'SELECT study_month , sum(study_time)as sum_time  , round(AVG(study_time),1)as avg_time  FROM study_detail WHERE user_id = :user_id  GROUP BY study_month desc';


    $data = array(':user_id' => $user_id);
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      debug('成功');
      return $stmt->fetchall();
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function getSessionFlash($keyword)
{
  debug('flashスタート：' . print_r($keyword, true));
  debug('セッション変数の：' . print_r($_SESSION, true));
  if (!empty($_SESSION[$keyword])) {
    debug('flashスタート：' . print_r($keyword, true));
    $flash = $_SESSION[$keyword];
    debug('$flash' . print_r($flash, true));
    $_SESSION[$keyword] = '';
    debug('$flash2' . print_r($flash, true));
    return $flash;
  }
}
