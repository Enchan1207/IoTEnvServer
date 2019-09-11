<?php
    /*
     * ESP02からのリクエストをもとにデータベースに値を保存する
    */

    //--クラスのrequire
    require "lib/DBAccess.php"; //DB接続
    require "lib/Log.php"; //ログ操作

    //--サニタイズ
    $post=array();
    foreach( $_POST as $key => $value ) {
        $post[$key] = htmlspecialchars( $value, ENT_QUOTES);
    }

    //--必要なデータがPOSTされていなければexit
    if(!isset($post['deviceID'], $post['temp'], $post['humid'])){
        print "Error. required data has not been POST.\r\n";
        exit;
    }

    //--ログに追加
    $postTime = time();
    $logger = new Log("db/log.db");
    $logger -> addValue($post['deviceID'], $postTime, (float)$post['temp'], (float)$post['humid']);

    //--一応まともなレスポンスを返してあげないとね
    print "Data Added as " . $post['deviceID'] ."\r\n when " . date("Y/m/d H:i:s", $postTime) . ".\r\n";
?>