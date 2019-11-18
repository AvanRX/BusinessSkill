<?php
$fp=fopen("log.txt","r+");

flock($fp,LOCK_EX);
$count=fgets($fp);
ftruncate($fp,0);
fseek($fp,0);
fwrite($fp,(int)$count+1);
flock($fp,LOCK_UN);

fclose($fp);

echo $count;
echo "\n";