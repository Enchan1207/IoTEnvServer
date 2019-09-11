<?php
    /*
     * ESP02からのリクエストをもとにデータベースに値を保存する
    */

    //--クラスのrequire
    require "lib/DBAccess.php"; //DB接続
    require "lib/Log.php"; //ログ操作

    $logger = new Log("db/log.db");

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
    $auth = $logger -> searchFrom($post['deviceID']);
    if(!$auth){
        header('HTTP', true, 400);
        exit;
    }

    //--ログに追加
    $postTime = time();
    $logger -> addValue($post['deviceID'], $postTime, (float)$post['temp'], (float)$post['humid']);

    print "Data Added as " . $post['deviceID'] ."\r\n when " . date("Y/m/d H:i:s", $postTime) . ".\r\n";
    exit;
?>