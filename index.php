<?php
    /*
     * ESP02からのリクエストをもとにデータベースに値を保存する
    */

    //--クラスのrequire
    require "lib/DBAccess.php";
    require "lib/Log.php";
    require "lib/Auth.php";

    $logger = new Log("db/log.db");
    $auth = new Auth("db/device.db");

    //--サニタイズ
    $post = array();
    foreach( $_POST as $key => $value ) {
        $post[$key] = htmlspecialchars( $value, ENT_QUOTES);
    }

    //--必要なデータがPOSTされていなければエラー
    if(!isset($post['deviceID'], $post['temp'], $post['humid'])){
        header('HTTP', true, 400);
        exit;
    }

    //--デバイスIDをデバイステーブルから検索し、正規リクエストか判定
    if(!$auth -> searchFrom($post['deviceID'])){
        header('HTTP', true, 400);
        exit;
    }

    //--ログに追加
    $postTime = time();
    $logger -> addValue($post['deviceID'], $postTime, (float)$post['temp'], (float)$post['humid']);

    print "Data Added as " . $post['deviceID'] ."\r\n when " . date("Y/m/d H:i:s", $postTime) . ".\r\n";
    exit;
?>