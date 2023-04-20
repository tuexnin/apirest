<?php

require_once("get.model.php");

class Connection {
    /**
     * informacion de la base de datos
     */

    static public function infoDatabase(){
        $infoDB = array(
            "database" => "redireccionador",
            "user" => "root",
            "pass" => ""
        );

        return $infoDB;
    }

    /**
     * informacion de la ApiKey
     */

    static public function apikey(){
        return "q9V;rE=:eYJa:Wwt.)8/hfA,n%G$=/";
    }

    /**
     * informacion de la ApiKey
     */

    static public function publicAcces(){
        return ["urls"];
    }

    /**
      * conexion a la base de datos
      */

    static public function connect(){
        try {
            $link = new PDO(
                "mysql:host=localhost;dbname=".Connection::infoDatabase()["database"],
                Connection::infoDatabase()["user"],
                Connection::infoDatabase()["pass"]
            );

            $link -> exec("set names utf8");
        } catch (PDOException $e) {
            die("Error: ".$e->getMessage());
        }

        return $link;
    }

    /**
     * Validar existencia de una tabla en la DB
     */

    static public function getColumsData($table, $columns){

        /**
         * traer el nombre de la DB
         */
        $database = Connection::infoDatabase()["database"];

        /**
         * trae todas las columnas de una tabla
         */
        $validate = Connection::connect()->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")->fetchAll(PDO::FETCH_OBJ);

        /**
         * valida la existencia de la tabla
         */
        if(empty($validate)){
            return null;
        }else{
            /**
             * ajuste de solicitud a columnas globales
             */

            if($columns[0] == "*"){
                array_shift($columns);
            }

            /**
             * valida la existencia de columnas
             */

            $sum = 0;
            foreach($validate as $key => $value){
                $sum += in_array($value->item, $columns);
            }

            return $sum == count($columns) ? $validate : null;
        }
    }

    /**
     * generar Token de autentificacion
     */

    static public function jwt($id, $email){

        $time = time();

        $token = array(
            "iat" => $time, //tiempo en el que inicia el token
            "exp" => $time + (60*60*24), //tiempo de expiracion del token
            "data" => [
                "id" => $id,
                "email" => $email
            ]
        );

        return $token;
    }

    /**
     * validar el token de seguridad
     */

    static public function tokenValidate($token){

        /**
         * trae el usuario de acuerdo al token
         */

        $user = GetModel::getValidToken($token);

        if(!empty($user)){
            /**
             * valida que el token haya expirado
             */

            $time = time();
            if($time < $user[0]->{"token_exp"}){
                return "ok";
            }else{
                return "expired";
            }
            
        }else{
            return "no-auth";
        }
        
    }

}