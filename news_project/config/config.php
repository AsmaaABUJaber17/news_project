<?php
$ServerName ="localhost";
$username="root";
$password="";
$dbname="schema";
$conn= mysqli_connect($ServerName,$username,$password,$dbname);
if(!$conn){
echo ("فشل الاتصال".mysqli_connect_error());}
/*else {
 /*  echo "connect";
 }*/
?>
