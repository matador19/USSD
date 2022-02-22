<?php
include_once 'index.php';
include_once 'menu.php';

function checkpin($textArray,$senderPhoneNumber){
    $pin = $textArray[3];
    // $pin = hash('sha256',$pin);
     $conn = opencon();
     $sql = "SELECT * FROM customer_table WHERE PhoneNumber='$senderPhoneNumber'";
     $result = $conn->query($sql);
     $menu=new Menu();
     if($result->num_rows>0){
         while($row = mysqli_fetch_assoc($result)) {
             $Senderpin=$row['PIN'];
           }
         }
         $conn->close();

         if($pin!='1010'){
            echo"CON pin wrong";
            array_pop($textArray);
           $text = join('*',$textArray);
            
          //  pin = array[3]+1

           // check if pin correct

        }
}
?>