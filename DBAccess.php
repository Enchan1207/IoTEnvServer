<?php
    /*
     * DB.iniの情報をもとにデータベースに接続する
    */

    require "DB.ini.php";

    //--DB接続クラス
    class DBAccess{
        //--プロパティ
        private $pdo;
        private $stmt;

        //--コンストラクタ
        function __construct(){
            //--文字コード指定
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

            //--pdoのインスタンスを生成
            try {
                $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD, $options);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "DB Connection Failed.".$e->getMessage();
                $pdo=null;
            }

            //プロパティにpdoをセット
            $this -> pdo = $pdo;
        }

        //--SQL実行
        function queryExec($sql, $paramarray){
            $this -> stmt = $this -> pdo -> prepare($sql); //なんだこれ
            $this -> stmt -> execute($paramarray);
        }

        //--fetchして配列を返す
        function fetchArray($property = PDO::FETCH_ASSOC){
            $result = array();
            while($rst = $this -> stmt -> fetch(PDO::FETCH_ASSOC)) {
                array_push($result, $rst);
            }
            return $result;
        }

    }
?>