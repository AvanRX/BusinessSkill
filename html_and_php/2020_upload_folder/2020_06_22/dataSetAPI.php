<?php

ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);

//定数の定義 - 今回はデフォルト値を定義
define('DEFAULT_LV', 1);
define('DEFAULT_EXP', 1);
define('DEFAULT_MONEY', 3000);

$dsn  = 'mysql:dbname=sgrpg;host=127.0.0.1';
$user = 'senpai';
$pw   = 'indocurry';

$sql1 = 'INSERT INTO User(lv, exp, money) VALUES(:lv, :exp, :money)';
$sql2 = 'SELECT LAST_INSERT_ID() as id';

try{
    $dbh = new PDO($dsn, $user, $pw);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sth = $dbh->prepare($sql1);//インサート命令でデータをデータベースに挿入
    $sth->bindValue(':lv',    DEFAULT_LV,    PDO::PARAM_INT);//命令引数にパラメータを設定
    $sth->bindValue(':exp',   DEFAULT_EXP,   PDO::PARAM_INT);
    $sth->bindValue(':money', DEFAULT_MONEY, PDO::PARAM_INT);

    $sth->execute();

    $sth = $dbh->prepare($sql2);//最後に追加したデータのIDを取得

    $sth->execute();
    $buff = $sth->fetch(PDO::FETCH_ASSOC);
  }
  catch( PDOException $e ) {
    sendResponse(false, 'Database error: '.$e->getMessage());
    exit(1);
  }

  if( $buff === false ){
    sendResponse(false, 'Database error: can not fetch LAST_INSERT_ID()');
  }
  else{
    sendResponse(true, $buff['id']);
  }

  function sendResponse($status, $value=[]){
    header('Content-type: application/json');
    echo json_encode([
      'status' => $status,
      'result' => $value
    ]);
  }