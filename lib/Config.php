<?php
    /*
     * 各デバイスのコンフィグを取得 
    */

    class Config extends DBAccess{
        //--コンストラクタ
        function __construct($dbname){
            //親クラスDBAのコンストラクタを呼び出す
            parent::__construct($dbname);
        }

        //--
        public function getConfig($deviceID, $type){
            $query = "SELECT * from configTable WHERE deviceID=?";
            $paramarray = array($deviceID);
            $this -> queryExec($query, $paramarray);
            $result = $this -> fetchArray();

            switch ($type) {
                case 'showID':
                    return $result[0]['showID'] == 1;
                    break;
                
                default:
                    return false;
                    break;
            }
        }

    }
?>