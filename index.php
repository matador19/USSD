<?php  
    include_once 'menu.php';  
    
    $isUserRegistered = true;

    // Read the data sent via POST from our AT API
    $sessionId   = $_POST["sessionId"];
    $serviceCode = $_POST["serviceCode"];
    $phoneNumber = $_POST["phoneNumber"];
    $text        = $_POST["text"];


    
    $menu = new Menu();
    $text = $menu->middleware($text);
    //$text = $menu->goBack($text);
    
    if($text == "" && $isUserRegistered == true){
         //user is registered and string is is empty
        echo "CON " . $menu->mainMenuRegistered("Alex");
    }else if($text == "" && $isUserRegistered== false){
         //user is unregistered and string is is empty
         $menu->mainMenuUnRegistered();

    }else if($isUserRegistered== false){
        //user is unregistered and string is not empty
        $textArray = explode("*", $text);
        switch($textArray[0]){
            case 1: 
                $menu->registerMenu($textArray, $phoneNumber);
            break;
            default:
                echo "END Invalid choice. Please try again";
        }
    }else{
        //user is registered and string is not empty
        $textArray = explode("*", $text);
        switch($textArray[0]){
            case 1: 
                $menu->sendMoneyMenu($sessionId,$textArray,$phoneNumber);
            break;
            case 2: 
                $menu->withdrawMoneyMenu($textArray);
            break;
            case 3:
                $menu->checkBalanceMenu($textArray);
                break;
            case 4:
                $menu->myAccount($textArray);
                break;
            default:
                echo "END Inavalid menu\n";
        }
    }

    

?>