<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);

$uid = isset($_GET['uid'])?  $_GET['uid']:null;

if( ($uid === null) || (!is_numeric($uid)) ){
  sendResponse(false, 'Invalid uid');
  exit(1);
}

$dsn  = 'mysql:dbname=sgrpg;host=127.0.0.1';
$user = 'senpai';
$pw   = 'indocurry';

$sql = 'SELECT * FROM User WHERE id=:id';

try{
  $dbh = new PDO($dsn, $user, $pw);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sth = $dbh->prepare($sql);

  $sth->bindValue(':id', $uid, PDO::PARAM_INT);

  $sth->execute();

  $buff = $sth->fetch(PDO::FETCH_ASSOC);
}
catch( PDOException $e ) {
  sendResponse(false, 'Database error: '.$e->getMessage());
  exit(1);
}

if( $buff === false ) {
    sendResponse(false, 'Not Found user');
}
else{
    sendResponse(true, $buff);
}

function sendResponse($status, $value=[]){
    header('Content-type: application/json');
    echo json_encode([
      'status' => $status,
      'result' => $value
    ]);
 }