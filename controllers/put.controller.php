<?php

require_once "models/put.model.php";

class PutController{

    /**
     * Peticion PUT para editar datos
     */

    static public function putData($table, $data, $id, $nameId){
        $response = PutModel::putData($table, $data, $id, $nameId);
        $return = new PutController();
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
                'method' => 'PUT'
            );
        }


        echo json_encode($json, http_response_code($json['status']));
    }
}

?>