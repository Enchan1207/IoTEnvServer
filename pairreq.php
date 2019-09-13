<?php
    require "lib/DBAccess.php";
    require "lib/Pairreq.php";

    //--ペアリングリクエストが来ているかのみ判定(デバイス用)
    if(!isset($_POST['deviceID'])){
        header('HTTP', true, 400);
        exit;
    }
    $preq = new Pairreq("db/device.db");
    $reqcount = count($preq -> getPairRequiredUser((int)htmlspecialchars($_POST['deviceID'])));

    if($reqcount == 0){
        print "";
    }else{
        print "@123456";
    }
    exit;
?>