<?php
require_once('define.php');

ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);

define('MAX_CHARA',10);
define('GACHA_PRICE',300);

$uid=isset($_GET['uid'])?$_GET['uid']:null;

if(($uid==null)||(!is_numeric($uid))){
    sendResponse(false,'Invalid uid');
    exit(1);
}

$sql1='SELECT money From User WHERE id=:userid';//所持金取得
$sql2='UPDATE User SET money=money-:price WHERE id=userid';//所持金からガチャ一回分減算
$sql3='INSERT INTO UserChara(user_id,chara_id) VALUES(:userid,:charaid)';
$sql4='SELECT * FROM Chara WHERE id=:charaid';

try{
    $dbh=new PDO(DEFINE::$dsn,DEFINE::$user,DEFINE::$pw);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $dbh->beginTransaction();

    $sth=$dbh->prepare($sql1);
    $sth->bindValue(':userid',$uid,PDO::PARAM_INT);
    $sth->execute();
    $buff=$sth->fetch(PDO::FETCH_ASSOC);

    if($buff==false){
        sendResponse(false, 'Not Found User');
        exit(1);
    }
    
    if($buff['money']<GACHA_PRICE){
        sendResponse(false, 'The balance is not enough');
        exit(1);
    }

    $sth=$dbh->prepare($sql2);
    $sth->bindValue(':price',GACHA_PRICE,PDO::PARAM_INT);
    $sth->bindValue(':userid',PDO::PARAM_INT);
    $sth->execute();

    $charaid=random_int(1,MAX_CHARA);

    $sth=$dbh->prepare($sql3);
    $sth->bindValue(':userid',$uid,PDO::PARAM_INT);
    $sth->bindValue(':charaid',$charaid,PDO::PARAM_INT);
    $sth->execute();

    $sth=$dbh->prepare($sql4);
    $sth->bindValue(':charaid',$charaid,PDO::PARAM_INT);
    $sth->execute();
    $chara=$sth->fetch(PDO::FETCH_ASSOC);

    $dbh->commit();
}
catch(PDOException $e){
    $dbh->rollBack();
    sendResponse(false, 'Database error: '.$e->getMessage()); 
    exit(1);
}

if( $buff === false ){
    sendResponse(false, 'System Error');
}
else{
    sendResponse(true, $chara);
}

function sendResponse($status, $value=[]){
    header('Content-type: application/json');
    echo json_encode([
      'status' => $status,
      'result' => $value
    ]);
}