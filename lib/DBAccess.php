<?php
    /*
     * データベースに接続する
    */

    //--DB接続クラス
    class DBAccess{
        //--プロパティ
        private $pdo;
        private $stmt;

        //--コンストラクタ
        function __construct($target){
            //--文字コード指定
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

            //--pdoのインスタンスを生成
            try {
                $pdo = new PDO("sqlite:$target", "", "",$options);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "DB Connection Failed." . $e -> getMessage();
                $pdo = null;
            }

            //プロパティにpdoをセット
            $this -> pdo = $pdo;
        }

        //--SQL実行
        function queryExec($sql, $paramarray){
            $this -> stmt = $this -> pdo -> prepare($sql); //なんだこれ
            $this -> stmt -> execute($paramarray);
        }

        //--SQL実行(bindParam)
        function bindExec($sql, $bindarray){
            $this -> stmt = $this -> pdo -> prepare($sql);
            foreach ($bindarray as $key => $value) {
                $this -> stmt -> bindValue(":$key", $value);
            }
            $this -> stmt -> execute();
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