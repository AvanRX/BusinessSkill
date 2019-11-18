<?php
require_once("common.php");

//クエリー(引数)を受け取る
$qid=$_GET['question'];
$answer=$_GET['answer'];

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

