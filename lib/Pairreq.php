<?php
    /*
     * ペアリングリクエストを検索 
    */

    class Pairreq extends DBAccess{
        //--コンストラクタ
        function __construct($dbname){
            //親クラスDBAのコンストラクタを呼び出す
            parent::__construct($dbname);
        }

        //--紐づけられていて、ペアリングを要求しているユーザリストを返す
        public function getPairRequiredUser($deviceID){
            $query = "SELECT * FROM deviceTable WHERE deviceID=? AND reqstat=1";
            $paramarray = array($deviceID);
            $this -> queryExec($query, $paramarray);
            $result = $this -> fetchArray();
            return $result;
        }
    }
?>