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

function get_retries($sessionId){
  $retry=0;
  $conn = opencon();
  $sql = "SELECT * FROM session_requests WHERE SessionID='$sessionId'";
  $result = $conn->query($sql);
  if($result->num_rows>0){
      while($row = mysqli_fetch_assoc($result)) {
          $retry=$row['Retries'];
        }
      }
      $conn->close();
      return $retry;
  }
function update_retries($sessionId,$retry){
          $conn = opencon();
          $sql = "UPDATE session_requests SET Retries=$retry WHERE SessionID='$sessionId'";
          if ($conn->query($sql) === TRUE) {
              //
            } else {
               $conn->error;
            }
            $conn->close();
            
   }
  function save_Session($sessionId){
    $conn = opencon();
  
    $sql = "SELECT * FROM session_requests WHERE SessionID='$sessionId'";
  $result = $conn->query($sql);
  if($result->num_rows>0){
      //
      }
      else{
            $sql = "INSERT INTO session_requests (SessionID) VALUES ('$sessionId')";
            if ($conn->query($sql) === TRUE) {
               //
            }
          }

                $conn->close();
  }

   ?>
   