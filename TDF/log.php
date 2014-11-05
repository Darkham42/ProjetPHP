<?php 
$logfile="C:\\Users\\Thomas\\AppData\\Local\\Temp\\resource.txt";
$fp = fopen($logfile,"a+");

function traceLog($fp,$message) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $time = date("l, j F Y [H:i:s]");

        fputs($fp,"$time $ip  : $message\n");
}
?>