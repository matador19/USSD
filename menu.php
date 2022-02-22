<?php
include_once 'mydb.php';
    include_once 'util.php';
    include_once 'sms.php';
    //include_once 'functions.php';
    class Menu{
        protected $text;
        protected $sessionId;

        function __construct(){}

        public function mainMenuRegistered($name){
            //shows initial user menu for registered users
            $response = "Welcome " . $name . " Reply with\n";
            $response .= "1. Send money\n";
            $response .= "2. Withdraw\n";
            $response .= "3. Check balance\n";
            $response .= "4. My Account\n";
            return $response;
        }

        public function mainMenuUnRegistered(){
            //shows initial user menu for unregistered users
            $response = "CON Welcome to this app. Reply with\n";
            $response .= "1. Register\n";
            echo $response;
        }

        public function registerMenu($textArray, $phoneNumber){
          //building menu for user registration 
            $level = count($textArray);
           if($level == 1){
                echo "CON Please enter your full name:";
                //textarray1
           } else if($level == 2){
                echo "CON Please enter set you PIN:";
                //textarray2
           }else if($level == 3){
                echo "CON Please re-enter your PIN:";
           }else if($level == 4){
               $nameexplode = explode(" ",$textArray[1]);
                $Fname = $nameexplode[0];
                $Lname = $nameexplode[1];
                $pin = $textArray[2];
                $confirmPin = $textArray[3];
                if($pin != $confirmPin){
                    echo "END Your pins do not match. Please try again";
                }else{
                    //connect to DB and register a user. 
                    $conn=opencon();

                $pin = hash('sha256',$pin);
                $sql = "INSERT INTO customer_table  (Fname, Lname , PhoneNumber , PIN)  VALUES ('$Fname', '$Lname', '$phoneNumber','$pin')";
                if ($conn->query($sql) === TRUE) {
                    echo "END You have been registered";
                    
                } else {
                        $conn->error;
                        echo "END not registered";
                        }        
                    $conn->close();
                   // $sms = new Sms();
                   // $message = "You have been registered";
                   // $sms->sendSms($message,$phoneNumber);
                }
           }
        }

        public function sendMoneyMenu($sessionId, $textArray, $senderPhoneNumber){
            //building menu for user registration 
            $level = count($textArray);
            $receiver = null;
            $nameOfReceiver = null;
            $response = "";
            $retry=0;;
            $text = $_POST["text"];
            if($level == 1){
                echo "CON Enter mobile number of the receiver:";
            }else if($level == 2){
                echo "CON Enter amount:";
            }else if($level == 3){
                echo "CON Enter your PIN:";
            }else if($level == 4){
                //check pin
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
                    
                    $conn = opencon();
                    $text= join('*',$textArray);
                    $sql = "INSERT INTO session_requests (SessionID, Responses,Retries) VALUES ('$sessionId','$text',0)";
                    if ($conn->query($sql) === TRUE) {
                       //
                    }

                        $conn->close();

                       

                    if($pin!='1010'){
                        echo"CON pin wrong";
                        array_pop($textArray);
                       $text = join('*',$textArray);
                       $_POST['text']=$text;
                        
                      //  pin = array[3]+1

                       // check if pin correct

                    }
                    else{
                $receiverMobile = $textArray[1];
                $receiverMobileWithCountryCode = $this->addCountryCodeToPhoneNumber($receiverMobile);
                
                $response .= "Send " . $textArray[2] . " to <Put a person's name here> - " . $receiverMobileWithCountryCode . "\n";
                $response .= "1. Confirm\n";
                $response .= "2. Cancel\n";
                $response .= Util::$GO_BACK . " Back\n";
                $response .= Util::$GO_TO_MAIN_MENU .  " Main menu\n";
                echo "CON " . $response;
                    }
            }else if($level == 5+$retry && $textArray[4+$retry] == 1){
                //a confirm
                //send the money plus
                $receiverMobile = $textArray[1];
                $datetime = date_create()->format('Y-m-d H:i:s');
                $amount = $textArray[2];
            
                
                    $conn = opencon();
                    $sql = "SELECT * FROM customer_table WHERE PhoneNumber='$receiverMobile'";
                    $result = $conn->query($sql);
    
                    if($result->num_rows>0){
                        while($row = mysqli_fetch_assoc($result)) {
                            $Receivername=$row['FName'];
                          }
                          
                        
                    }
                    
                    
                    $sql="INSERT INTO receipts  (ReceiptNo, CustomerFrom, CustomerTo, PhoneNoFrom, PhoneNoTo, Amount, Time_stamp) 
                    VALUES (NULL,(SELECT FName FROM customer_table WHERE PhoneNumber = '$senderPhoneNumber' ), '$Receivername', '$senderPhoneNumber', '$receiverMobile', '$amount','$datetime')
                    ";
    
    if ($conn->query($sql) === TRUE) {
        successmessage();
        echo "END We are processing your request. You will receive an SMS shortly";
    } else {
            $conn->error;
            echo "END Failed!";
            }   
    
                    $conn->close();
                
                

   

                //connect to DB
                //Complete transaction

                


            }else if($level == 5 && $textArray[4] == 2){
                //Cancel
                echo "END Canceled. Thank you for using our service";
            }else if($level == 5 && $textArray[4] == Util::$GO_BACK){
                echo "END You have requested to back to one step - re-enter PIN";
            }else if($level == 5 && $textArray[4] == Util::$GO_TO_MAIN_MENU){
                echo "END You have requested to back to main menu - to start all over again";
            }else {
                echo "END Invalid entry"; 
            }
        }

        public function withdrawMoneyMenu($textArray){
            //TODO
            echo "CON To be implemented";
        }

        public function checkBalanceMenu($textArray){
            echo "CON To be implemented";
        }

        public function myAccount($textArray){
            $level = count($textArray);
            if($level == 1){
                $response = "CON Choose an Option\n";
                $response .= "1. Change Pin\n";
                $response .= "2. Forgot Pin\n";
                echo $response;
            }
            else{
                switch($textArray[1]){
                    case 1: 
                        if($level == 2){
                            echo "CON Enter Old Pin\n";
                        }
                        else if($level == 3){
                             echo "CON Enter New Pin\n";
                        }
                        else if($level == 4){
                            $old_pin = $textArray[3];
                            $new_pin = $textArray[4];
                             echo "END Old Pin:".$old_pin."\nNew Pin:".$new_pin."\n";
                        }

                    break;
                    case 2: 
                        echo "END Inavalid menu2\n";
                    break;
                    case 3:
                        echo "END Inavalid menu3\n";
                        break;
                    case 4:
                        echo "END Inavalid menu4\n";
                        break;
                    default:
                        echo "END Inavalid menu5\n";
        }

            }

        }

        public function addCountryCodeToPhoneNumber($phone){
            return Util::$COUNTRY_CODE . substr($phone, 1);
        }

public function middleware($text){
    //remove entries for going back and going to the main menu
    return $this->goBack($this->goToMainMenu($text));
}

        public function goBack($text){
            //1*4*5*1*98*2*1234
            $explodedText = explode("*",$text);
            while(array_search(Util::$GO_BACK, $explodedText) != false){
                $firstIndex = array_search(Util::$GO_BACK, $explodedText);
                array_splice($explodedText, $firstIndex-1, 2);
            }
            return join("*", $explodedText);
        }

        public function goToMainMenu($text){
            //1*4*5*1*99*2*1234*99
            $explodedText = explode("*",$text);
            while(array_search(Util::$GO_TO_MAIN_MENU, $explodedText) != false){
                $firstIndex = array_search(Util::$GO_TO_MAIN_MENU, $explodedText);
                $explodedText = array_slice($explodedText, $firstIndex + 1);
            }
            return join("*",$explodedText);
        }
    }
?>