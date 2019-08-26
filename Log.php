<?php
    /*
     * DB上に保存されているログを操作する
    */

    //--ログ操作クラス
    class Log extends DBAccess{
        //--コンストラクタ
        function __construct(){
            //親クラスDBAのコンストラクタを呼び出す
            parent::__construct();
        }

        //--ログに値を追加
        public function addValue($deviceID, $postTime, $temp, $humid){
            //--データを追加
            $query = "INSERT INTO esp02dataTable (id, deviceID, postTime, temp, humid) VALUES (?, ?, ?, ?, ?)";
            $paramarray = array("0", $deviceID, $postTime, $temp, $humid);
            $this -> queryExec($query, $paramarray);
        }

        //--ログからパラメータをもとに値を取得
        public function getValue($userID, $deviceID, $limit){
            //--userIDとdeviceIDがペアリングされているかの確認も行いたい
            print $userID == $deviceID;

            //--limitで指定された個数分取り出す 0ですべて、正数で最新、負数で最古のデータから指定個数分取得
            $addQuery = "";
            if($limit > 0){
                $addQuery = "ORDER BY postTime DESC LIMIT $limit";
            }else if ($limit < 0){
                $addQuery = "ORDER BY postTime ASC LIMIT " . abs($limit);
            }
            $query = "SELECT * FROM esp02dataTable WHERE deviceID=? $addQuery";
            $paramarray = array($deviceID);
            $this -> queryExec($query, $paramarray);

            $result = $this -> fetchArray();
            return $result;
        }
        
        // $data = $obj -> getValue("", "esp02", 10);
        // foreach ($data as $cell) {
        //     print $cell['postTime']."<br>";
        // }
        // print "<hr>";
        // $data = $obj -> getValue("", "esp02", -10);
        // foreach ($data as $cell) {
        //     print $cell['postTime']."<br>";
        // }
        // print "<hr>";
        // $data = $obj -> getValue("", "esp02", 0);
        // foreach ($data as $cell) {
        //     print $cell['postTime']."<br>";
        // }
    }
?>