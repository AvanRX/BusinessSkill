<?php

//ini_set('display_errors',"On");//一応
define('DATA_FILE',"data.txt");

$count=getCounter(DATA_FILE);//値の取得

header('Content-type:application/json');
echo json_encode(['status'=>true,'count'=>$count]);//値を返却

function getCounter($file){

    $fp=fopen($file,'r+');
    flock($fp,LOCK_EX);
    $buff=(int)fgets($fp);

    ftruncate($fp,0);
    fseek($fp,0);

    fwrite($fp,$buff+1);

    flock($fp,LOCK_UN);
    fclose($fp);

    return($buff);
}

