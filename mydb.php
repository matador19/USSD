<?php
//include_once 'menu.php';
function opencon(){
 $servername = "localhost:3307";
 $username = "root";
 $password = "";
 $db_name = "ussd";
 
 // Create connection
 $conn = new mysqli($servername, $username, $password,$db_name);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}
   ?>
   