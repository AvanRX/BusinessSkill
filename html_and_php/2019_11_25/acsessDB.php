<?php
$dsn='mysql:dbname=rpgdb;host=localhost';
$user='senpai';
$pw='indocurry';

$sql='SELECT * FROM Monster';

try{    
    $dbh=new PDO($dsn,$user,$pw);
}
catch(PDOException $e){
    echo '/**********************************************/'."^n";
    echo 'Error:'.$e->getMessage()."\n";
}
$sth=$dbh->prepare($sql);
$sth->execute();

while(true){
    $tmp=$sth->fetch(PDO::FETCH_ASSOC);
    if($tmp==false){//データがとれなくなった(末尾に到達したかエラーの場合)
        break;
    }

    echo $tmp['id'].':'.$tmp['name']."\n";
}
