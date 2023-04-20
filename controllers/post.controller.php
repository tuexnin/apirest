<?php
require_once("models/connection.php");
require_once("models/get.model.php");
require_once("models/post.model.php");
require_once("models/put.model.php");
require_once("vendor/autoload.php");
use Firebase\JWT\JWT;

class PostController{

    /**
     * Peticion POST para crear datos
     */

    static public function postData($table, $data){
        $response = PostModel::postData($table, $data);
        $return = new PostController();
        $return -> fncResponse($response, null);
    }

    /**
     * Peticion POST para registrar usuario
     */

    static public function postUserRegister($table, $data){
        if(isset($data["password"]) && $data["password"] != null){
            $crypt = crypt($data["password"], '$2a$07$azybxcags23425sdg23sdfhsd$');
            $data["password"] = $crypt;
            $response = PostModel::postData($table, $data);
            $return = new PostController();
            $return -> fncResponse($response, null);
        }else{
            /**
             * registro de ususario desde App externas
             */

            $response = PostModel::postData($table, $data);

            if(isset($response["comment"]) && $response["comment"] == "The process was successfull"){

                /**
                 * valida que el usuario exista
                 */

                $response = GetModel::getDataFilter($table, "*", "email", $data["email"], null, null, null, null);
                if(!empty($response)){

                    $token = Connection::jwt($response[0]->id,$response[0]->email);
                    $jwt = JWT::encode($token, "fasdfasdfasg56465gas5dg", 'HS256');
                    /**
                     * actualiza la DB con el token del usuario
                     */

                    $data = array(
                        "token" => $jwt,
                        "token_exp" => $token["exp"]
                    );

                    $update = PutModel::putData($table, $data, $response[0]->id,"id");

                    if(isset($update["comment"]) && $update["comment"] == "The process was successfull"){
                        $response[0]->token = $jwt;
                        $response[0]->token_exp = $token["exp"];
                        $return = new PostController();
                        $return->fncResponse($response, null);
                    }
                }
            }
        }
    }

    /**
     * Peticion POST para login de usuario
     */

    static public function postLogin($table, $data){
        /**
         * valida que el usuario exista
         */

        $response = GetModel::getDataFilter($table, "*", "email", $data["email"], null, null, null, null);
        if(!empty($response)){
            
            if($response[0]->password != null){

                /**
                 * encriptamos la contraseña
                 */

                $crypt = crypt($data["password"], '$2a$07$azybxcags23425sdg23sdfhsd$');
                if($response[0]->password == $crypt){

                    $token = Connection::jwt($response[0]->id,$response[0]->email);
                    $jwt = JWT::encode($token, "fasdfasdfasg56465gas5dg", 'HS256');
                    /**
                     * actualiza la DB con el token del usuario
                     */

                    $data = array(
                        "token" => $jwt,
                        "token_exp" => $token["exp"]
                    );

                    $update = PutModel::putData($table, $data, $response[0]->id,"id");

                    if(isset($update["comment"]) && $update["comment"] == "The process was successfull"){
                        $response[0]->token = $jwt;
                        $response[0]->token_exp = $token["exp"];
                        $return = new PostController();
                        $return->fncResponse($response, null);
                    }

                }else{
                    $response = null;
                    $return = new PostController();
                    $return->fncResponse($response, "Wrong password");
                }
            }else{

                /**
                * actualiza el token para usuarios logeados desde App externas
                */

                $token = Connection::jwt($response[0]->id,$response[0]->email);
                $jwt = JWT::encode($token, "fasdfasdfasg56465gas5dg", 'HS256');
                
                $data = array(
                    "token" => $jwt,
                    "token_exp" => $token["exp"]
                );

                $update = PutModel::putData($table, $data, $response[0]->id,"id");

                if(isset($update["comment"]) && $update["comment"] == "The process was successfull"){
                    $response[0]->token = $jwt;
                    $response[0]->token_exp = $token["exp"];
                    $return = new PostController();
                    $return->fncResponse($response, null);
                }
            }

        }else{
            $response = null;
            $return = new PostController();
            $return->fncResponse($response, "Wrong email");
        }
    }

    /**
     * respuestas del controlador
     */

    public function fncResponse($response, $error)
    {

        if (!empty($response)) {

            /**
             * quitamos la contraseña de la respuesta
             */

            if(isset($response[0]->password)){
                unset($response[0]->password);
            }

            $json = array(
                'status' => 200,
                'results' => $response
            );
        } else {

            if($error != null){
                $json = array(
                    'status' => 400,
                    'results' => $error,
                    'method' => 'POST'
                );
            }else{
                $json = array(
                    'status' => 404,
                    'results' => 'Not Fund',
                    'method' => 'POST'
                );
            }

        }


        echo json_encode($json, http_response_code($json['status']));
    }
}

?>