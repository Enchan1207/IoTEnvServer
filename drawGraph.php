<?php
    /*
     * 測定データからグラフ生成
    */

    require "/home/r-techlab/jpgraph/jpgraph.php";
    require "/home/r-techlab/jpgraph/jpgraph_line.php";
    require "/home/r-techlab/jpgraph/jpgraph_date.php";
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

    //--取得したデータのうち、温湿度データおよびタイムスタンプのみ抽出して配列に格納
    $temp = array();
    $humid = array();
    $timestamp = array();
    foreach ($measureData as $data) {
        array_push($temp, $data['temp']);
        array_push($humid, $data['humid']);
        array_push($timestamp, $data['postTime'] + 9 * 3600); //時差を考慮
    }

    //--JpGraphをインスタンス化し、グラフを描画
    $graph = new Graph(400, 200);
    $graph -> SetScale("datlin", 25, 40);
    $graph -> SetY2Scale("lin", 0, 100);
    $graph -> SetMargin(50, 40, 20, 70); //lrud

    $graph -> xaxis -> scale -> SetDateFormat("H:i");
    $graph -> xaxis -> SetLabelAngle(80);
    $graph -> xaxis -> SetColor("#010101");

    //線を引く
    $lineplot_temp = new LinePlot($temp, $timestamp);
    $lineplot_temp -> SetWeight(2);
    $lineplot_humid = new LinePlot($humid, $timestamp);
    $lineplot_humid -> SetWeight(2);

    $graph -> Add($lineplot_temp);
    $graph -> AddY2($lineplot_humid);

    $lineplot_temp -> SetColor("#000080");
    $lineplot_humid -> SetColor("#800000");

    $graph -> Stroke();
?>