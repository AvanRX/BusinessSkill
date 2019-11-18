<?php
require_once("common.php");

//クエリー(引数)を受け取る
$qid=$_GET['question'];
$answer=$_GET['answer'];

//バリデーション(要は正しい値かをチェックしてる)
if($qid==-1||!is_numeric($qid)((0<=$qid)&&($qid<count($question)))){
    echo 'error : $qid invalid';
    exit(1);
}

if($question[$qid][1]==$answer){
    echo "正解！";
}
else{
    echo "残念!";
}
?>

<!DOCTYPE html>
<html>


</html>

