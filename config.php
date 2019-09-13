<?php
    /*
     * 各デバイスのコンフィグを取得 
    */

    require "lib/DBAccess.php";
    require "lib/Config.php";
    require "lib/Auth.php";

    //--
    if(!isset($_POST['type'], $_POST['deviceID'])){
        header('HTTP', true, 400);
        exit;
    }
    $type = htmlspecialchars($_POST['type']);
    $deviceID = htmlspecialchars($_POST['deviceID']);

    //--デバイスIDをデバイステーブルから検索し、正規リクエストか判定
    $auth = new Auth("db/device.db");
    if(!$auth -> searchFrom($deviceID)){
        header('HTTP', true, 400);
        exit;
    }

    //--指定タイプのコンフィグを取得
    $cfg = new Config("db/config.db");
    $result = $cfg -> getConfig($deviceID, $type);
    if($result){
        header('HTTP', true, 200);
        exit;
    }
    header('HTTP', true, 403);
    exit;
?>