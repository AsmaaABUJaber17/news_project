<?php
$ServerName ="localhost";
$username="root";
$password="";
$dbname="schema";
$conn = mysqli_connect("db", "root", "root", "nms_db");
if(!$conn){
echo ("فشل الاتصال".mysqli_connect_error());}
/*else {
 /*  echo "connect";
 }*/
?>
