<?php

require_once("connection.php");
require_once("get.model.php");

class DeleteModel{

    /**
     * peticion DELETE para eliminar datos de forma dinamica
     */

    static public function deleteData($table, $id, $nameId){

        /**
         * valida que el id exista
         */

        $response = GetModel::getDataFilter($table, $nameId, $nameId, $id, null, null, null, null);
        if(empty($response)){
            $response = array(
                "comment" => "Error: The id in not found in the database"
            );
            return $response;
        }

        /**
         * elimina el registro
         */

        $sql = "DELETE FROM $table WHERE $nameId = :$nameId";
        $link = Connection::connect();
        $stmt = $link->prepare($sql);
        $stmt -> bindParam(":".$nameId, $id, PDO::PARAM_STR);

        if($stmt ->execute()){
            $response = array(
                "comment" => "The process was successfull"
            );
            return $response;
        }else{
            return $link->errorInfo();
        }
        
    }

}

?>