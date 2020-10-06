<?php
$serverName = 'mysqlcontainer';
$userName = 'root';
$password = 'myrootpass';
$dbName = 'consultorio';

$link = mysqli_connect($serverName, $userName, $password, $dbName);

if (mysqli_connect_errno()) {
   printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
} else {
    echo "Connected";
}