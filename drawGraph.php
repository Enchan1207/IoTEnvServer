<?php
    /*--
     * サイズとデータ数を指定してグラフ生成
    --*/

    set_include_path("/home/r-techlab");
    require 'lib/DBAccess.php';
    require 'lib/Log.php';
    require 'jpgraph/jpgraph.php';
    require 'jpgraph/jpgraph_line.php';

    //--サニタイズ
    $get = array();
    foreach( $_GET as $key => $value ) {
        $get[$key] = htmlspecialchars( $value, ENT_QUOTES);
    }

    //--必要なデータがPOSTされていなければエラー
    if(!isset($get['deviceID'], $get['size'], $get['limit'])){
        header('HTTP', true, 400);
        exit;
    }

    //--同時に10万個、画像幅4桁以上のリクエストは受け付けない
    if(mb_strlen($get['limit']) > 5 || mb_strlen($get['size']) > 7){
        header('HTTP', true, 416); //416 Range Not Satisfiable
        exit;
    }
    $size_ = explode("x", $get['size']); //sizeは 400x300 のような形で送られることを想定
    $limit = abs((int)$get['limit']);
    //--サイズの指定が不正なら受け付けない
    if(count($size_) != 2){
        header('HTTP', true, 416); //416 Range Not Satisfiable
        exit;
    } 

    //--グラフに必要な情報
    $width = (int)$size_[0];
    $height = (int)$size_[1];
    $deviceID = $get['deviceID'];

    //--与えられた情報をもとにDBに接続し、描画対象のデータを取得
    $logger = new Log("db/log.db");
    $result = $logger -> getValue("", $deviceID, $limit);
    $temp = array();
    $humid = array();
    foreach ($result as $cell) {
        array_push($temp, $cell['temp']);
        array_push($humid, $cell['humid']);
    }

    //--グラフ描画

    //目盛とマージン、各軸タイトルの設定
    $graph = new Graph($width, $height);
    $graph -> SetScale("textlin");
    $graph -> SetY2Scale("lin");
    $graph -> SetMargin(50, 40, 20, 40); //lrud

    //線を引く
    $lineplot_temp = new LinePlot($temp);
    $lineplot_temp -> SetWeight(2);
    $lineplot_humid = new LinePlot($humid);
    $lineplot_humid -> SetWeight(2);

    $graph -> Add($lineplot_temp);
    $graph -> AddY2($lineplot_humid);

    $lineplot_temp -> SetColor("#000080");
    $lineplot_humid -> SetColor("#800000");

    $graph -> Stroke();
?>