<?php

//ini_set('display_errors',"On");//一応
define("DB_DSN","mysql:dbname=access;host=127.0.0.1");//接続先
define("DB_USER","senpai");//ID
define("DB_PW","indocurry");//パス

$dbh=connectDB(DB_DSN,DB_USER,DB_PW);//接続

addCounter($dbh);//レコード追加

$count=getCounter($dbh);//レコードカウント

header('Content-type:application/json');
echo json_encode(['status'=>true,'count'=>$count]);//値の返却

function connectDB($DSN,$USER,$PW){
    $dbh=new PDO($DSN,$USER,$PW);
    return($dbh);
}

function addCounter($dbh) {
    $sql='INSERT INTO access_log(accesstime) VALUES(now())';

    $sth=$dbh->prepare($sql);//SQLを解析
    $ret=$sth->execute();//実行
    return($ret);
}

function getCounter($dbh){

   $sql='SELECT count(*) as count FROM access_log';

   $sth=$dbh->prepare($sql);
   $sth->execute();

   $buff=$sth->fetch(PDO::FETCH_ASSOC);
   if($buff===false){
       return(false);
   }
   else{
       return($buff['count']);
   }
}