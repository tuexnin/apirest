<?php

require_once "models/connection.php";
require_once "controllers/get.controller.php";

$routesArray = explode("/", $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);

/**
 * CUANDO NO SE HACE NINGUNA PETICION A LA API
 */

if(count($routesArray) == 0){
    $json = array(
        'status' => 400,
        'results' => 'Not Found'
    );
    
    echo json_encode($json, http_response_code($json['status']));
    
    return;
}

/**
 * CUANDO SI SE HACE UNA PETICION A LA API
 */

if(count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"])){

    $table = explode("?", $routesArray[1])[0];

    /**
     * validar llave secreta
     */
    if(!isset(getallheaders()["apikey"]) || getallheaders()["apikey"] != Connection::apikey()){


        if(in_array($table, Connection::publicAcces()) == 0){

            $json =array(
                'status' => 400,
                'results' => "Error: You are not autorized to make this request"
            );
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }else{
            /**
             * acceso publico
             */
            $response = new GetController();
            $response -> getData($table, "*", null, null, null, null);
            return;
        }
    }

    
    /**
     * peticiones GET
     */
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        include "services/get.php";
    }

    /**
     * peticiones POST
     */
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        include "services/post.php";
    }

    /**
     * peticiones PUT
     */
    if($_SERVER["REQUEST_METHOD"] == "PUT"){
        include "services/put.php";
    }

    /**
     * peticiones DELETE
     */
    if($_SERVER["REQUEST_METHOD"] == "DELETE"){
        include "services/delete.php";
    }
}

