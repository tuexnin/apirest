<?php

require_once("connection.php");

class PostModel{

    /**
     * peticion POST para crear datos de forma dinamica
     */

    static public function postData($table, $data){

        $colums = "";
        $params = "";
        foreach ($data as $key => $value) {
            $colums .= $key.",";
            $params .= ":".$key.",";
        }

        $colums = substr($colums, 0, -1);
        $params = substr($params, 0, -1);

        $sql = "INSERT INTO $table ($colums) VALUES ($params)";
        $link = Connection::connect();
        $stmt = $link->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt -> bindParam(":".$key, $data[$key], PDO::PARAM_STR);
        }

        if($stmt ->execute()){
            $response = array(
                "lastId" => $link->lastInsertId(),
                "comment" => "The process was successfull"
            );
            return $response;
        }else{
            return $link->errorInfo();
        }

        
    }

}

?>