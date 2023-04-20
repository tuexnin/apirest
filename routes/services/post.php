<?php

require_once("models/connection.php");
require_once("controllers/post.controller.php");

if(isset($_POST)){

    $colums = array();
    $response = new PostController();

    foreach(array_keys($_POST) as $key => $value){
        array_push($colums, $value);
    }
    
    /**
     * valida las tablas y las columnas
     */
    if(empty(Connection::getColumsData($table, $colums))){
        $json =array(
            'status' => 400,
            'results' => "Error: Fields in the form do not match the database"
        );

        echo json_encode($json, http_response_code($json["status"]));
    }


    /**
     * peticion POST para el registro de usuario
     */

    if(isset($_GET["register"]) && $_GET["register"] == true){

        $response -> postUserRegister($table, $_POST);

        /**
     * peticion POST para el login de usuario
     */

    }else if(isset($_GET["login"]) && $_GET["login"] == true){

        $response -> postLogin($table, $_POST);

    }else{

        /**
         * peticion POST para usuarios autorizados
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
             * solicita respuesta al controlador para crear datos en cualquier tabla
             */ 
            if($validate == "ok"){
                
                $response -> postData($table, $_POST);
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
}

?>