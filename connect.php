<?php
$server ="sql301.infinityfree.com";
$username ="if0_40211956";
$password ="Maghirang090605";
$dbname ="if0_40211956_website";

$conn = mysqli_connect($server,$username,$password,$dbname);

if(!$conn){
    die("Connect Failed:".mysqli_connect_error());
}
?>