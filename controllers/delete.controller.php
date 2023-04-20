<?php

require_once "models/delete.model.php";

class DeleteController{

    /**
     * Peticion DELETE para eliminar datos
     */

    static public function DeleteData($table, $id, $nameId){
        $response = DeleteModel::deleteData($table, $id, $nameId);
        $return = new DeleteController();
        $return -> fncResponse($response);
    }

    /**
     * respuestas del controlador
     */

    public function fncResponse($response)
    {

        if (!empty($response)) {
            $json = array(
                'status' => 200,
                'results' => $response
            );
        } else {
            $json = array(
                'status' => 404,
                'result' => 'Not Fund',
                'method' => 'DELETE'
            );
        }


        echo json_encode($json, http_response_code($json['status']));
    }
}

?>