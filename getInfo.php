<?php
    /*
     * デバイス情報を取得
    */

    require "lib/DBAccess.php";
    require "lib/Config.php";
    require "lib/Auth.php";
    require "lib/Log.php";

    //--GETパラメータ取得
    $get = array();
    foreach( $_GET as $key => $value ) {
        $get[$key] = htmlspecialchars( $value, ENT_QUOTES);
    }
    if(!isset($get['deviceID'])){
        exit;
    }
    $deviceID = $get['deviceID'];

    //--リクエストしたデータがデバイステーブルにあるか調べる(不正アクセス回避)
    $auth = new Auth("db/device.db");
    if(!$auth -> searchFrom($deviceID)){
        exit;
    }

    //--テーブルにあれば、デバイスリストに最低限必要な情報を入れて送り返す
    $logger = new Log("db/log.db");
    $lastdata = $logger -> getValue($deviceID, 1);

    $responce = array(
        "name" => "",
        "deviceID" => (int)$deviceID,
        "type" => 0,
        "lastData" => array(
            "timestamp" => (int)$lastdata['postTime'],
            "temp" => (float)$lastdata['temp'],
            "humid" => (float)$lastdata['humid']
        )
    );
    
    print json_encode($responce);
?>