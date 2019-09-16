<?php
    /*
     * 測定データからjson生成
    */

    require "lib/DBAccess.php";
    require "lib/Auth.php";
    require "lib/Log.php";

    //--グラフ作成に必要なデータをGETパラメータから受け取る
    $get = array();
    foreach( $_GET as $key => $value ) {
        $get[$key] = htmlspecialchars( $value, ENT_QUOTES);
    }
    if(!isset($get['deviceID'], $get['length'])){
        exit;
    }
    $deviceID = $get['deviceID'];
    $length = $get['length'];

    //--リクエストしたデータがデバイステーブルにあるか調べる(不正アクセス回避)
    $auth = new Auth("db/device.db");
    if(!$auth -> searchFrom($deviceID)){
        exit;
    }

    //--DBにつないで測定データを取得
    $log = new Log("db/log.db");
    $measureData = $log -> getValue($deviceID, $length);

    //--取得したデータをiOS側のフォーマットに合わせたjsonに変換し、レスポンスを返す
    $temp = array();
    $humid = array();
    $timestamp = array();

    $responce = array("log" => array());
    foreach ($measureData as $data) {
        $datacell = array(
            "temp" => (float)$data['temp'],
            "humid" => (float)$data['humid'],
            "timestamp" => (int)$data['postTime']
        );
        array_push($responce['log'], $datacell);
    }
    print json_encode($responce);
?>