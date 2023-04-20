<?php
require_once("models/connection.php");
require_once("controllers/put.controller.php");

if(isset($_GET["id"]) && isset($_GET["nameId"])){
    /**
     * capturamos los datos datos del formulario
     */

    $data = array();
    parse_str(file_get_contents('php://input'), $data);

    /**
     * separar propiedades en un arreglo
     */

    $columns = array();
    foreach (array_keys($data) as $key => $value) {
        array_push($columns, $value);
    }

    array_push($columns, $_GET["nameId"]);
    $columns = array_unique($columns);

    /**
     * validar la tabla y las columnas
     */

    if(empty(Connection::getColumsData($table, $columns))){
        $json = array(
            'status' => 400,
            'results' => "Error: Fields in the form do not match the database"
        );
        echo json_encode($json, http_response_code($json["status"]));
        return;
    }



    /**
    * peticion PUT para usuarios autorizados
    */
    $headers = null;
    if(isset($_SERVER['Authorization'])){
        $headers = trim($_SERVER["Authorization"]);
    }else if(isset($_SERVER['HTTP_AUTHORIZATION'])){
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    }else if(function_exists('apache_request_headers')){
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords',array_keys($requestHeaders)),array_values($requestHeaders));
        if(isset($requestHeaders['Authorization'])){
            $headers = trim($requestHeaders['Authorization']);
        }
    }

    if(!empty($headers)){

        $validate = Connection::tokenValidate($headers);

        /**
         * solicita respuesta al controlador para editar datos en cualquier tabla
         */ 
        if($validate == "ok"){
            
            $response = new PutController();
            $response -> putData($table, $data, $_GET["id"], $_GET["nameId"]);
        }

        /**
         * Error cuando el token ha expirado
         */
        if($validate == "expired"){
            $json =array(
                'status' => 303,
                'results' => "Error: The token has espired"
            );
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }

        /**
         * Error cuando el token no coincide en DB
         */

        if($validate == "no-auth"){
            $json =array(
                'status' => 400,
                'results' => "Error: The user is not authorized"
            );
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }
    }else{
        /**
         * Error cuando se solicita token para realizar una accion
         */
        $json =array(
            'status' => 400,
            'results' => "Error: Authorization required"
        );
        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    

}

?>