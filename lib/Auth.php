<?php
    /* 
     * デバイス認証
    */

    class Auth extends DBAccess{
        //--コンストラクタ
        function __construct($dbname){
            //親クラスDBAのコンストラクタを呼び出す
            parent::__construct($dbname);
        }

        //--認証テーブルから検索
        public function searchFrom($deviceID){
            $query = "SELECT * FROM deviceTable WHERE deviceID=?";
            $paramarray = array($deviceID);
            $this -> queryExec($query, $paramarray);
            $result = $this -> fetchArray();

            return count($result) == 1;
        }
    }
?>