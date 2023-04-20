<?php

require_once "connection.php";

class GetModel
{

    /**
     * peticion GET sin filtro
     */

    static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt)
    {

        /**
         * SIN ORDENAR Y LIMITAR DATOS
         */
        $sql = "SELECT $select FROM $table";

        /**
         * ORDENAR DATOS SIN LIMITES
         */
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
            $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
        }

        /**
         * ORDEMAR Y LIMITAR DATOS 
         */

        if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        /**
         * LIMITAR DATOS SIN ORDENAR
         */

        if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table LIMIT $startAt, $endAt";
        }


        $stmt = Connection::connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * peticion GET con filtro
     */

    static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt)
    {
        $linkToArray = explode(",", $linkTo);
        $equalToArray = explode("_", $equalTo);
        $linkToText = "";
        if (count($linkToArray) > 1) {
            foreach ($linkToArray as $key => $value) {
                if ($key > 0) {
                    $linkToText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }

        /**
         * SIN ORDENAR Y LIMITAR DATOS
         */

        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";

        /**
         * ORDENAR DATOS SIN LIMITES
         */

        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
        }

        /**
         * ORDEMAR Y LIMITAR DATOS 
         */

        if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        /**
         * LIMITAR DATOS SIN ORDENAR
         */

        if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText LIMIT $startAt, $endAt";
        }

        $stmt = Connection::connect()->prepare($sql);

        foreach ($linkToArray as $key => $value) {
            $stmt->bindParam(":" . $value, $equalToArray[$key], PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * peticion GET para ver si el token existe
     */

    static public function getValidToken($token)
    {
        /**
         * consulta
         */

        $sql = "SELECT token_exp FROM users WHERE token='$token'";

        $stmt = Connection::connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * peticion GET sin filtro entre tablas relacionadas
     */

    static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt)
    {

        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);

        $innerJoinText = "";
        if (count($relArray) > 1) {
            foreach ($relArray as $key => $value) {
                if ($key > 0) {
                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".".$typeArray[0]." = ".$value.".".$typeArray[$key]." ";
                }
            }


            /**
             * SIN ORDENAR Y SIN LIMITAR DATOS
             */
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText";

            /**
             * ORDENAR DATOS SIN LIMITES
             */
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode";
            }

            /**
             * ORDEMAR Y LIMITAR DATOS 
             */

            if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }

            /**
             * LIMITAR DATOS SIN ORDENAR
             */

            if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText LIMIT $startAt, $endAt";
            }


            $stmt = Connection::connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } else {
            return null;
        }
    }

    /**
     * peticion GET con filtro entre tablas relacionadas
     */

    static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt)
    {

        /**
         * Organizamos los filtros
         */
        $linkToArray = explode(",", $linkTo);
        $equalToArray = explode("_", $equalTo);
        $linkToText = "";
        if (count($linkToArray) > 1) {
            foreach ($linkToArray as $key => $value) {
                if ($key > 0) {
                    $linkToText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }

        /**
         * Organizamos las relaciones
         */

        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);

        $innerJoinText = "";
        if (count($relArray) > 1) {
            foreach ($relArray as $key => $value) {
                if ($key > 0) {
                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".".$typeArray[0]." = ".$value.".".$typeArray[$key]." ";
                }
            }


            /**
             * SIN ORDENAR Y SIN LIMITAR DATOS
             */
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";

            /**
             * ORDENAR DATOS SIN LIMITES
             */
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
            }

            /**
             * ORDEMAR Y LIMITAR DATOS 
             */

            if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }

            /**
             * LIMITAR DATOS SIN ORDENAR
             */

            if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText LIMIT $startAt, $endAt";
            }


            $stmt = Connection::connect()->prepare($sql);
            foreach ($linkToArray as $key => $value) {
                $stmt->bindParam(":" . $value, $equalToArray[$key], PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } else {
            return null;
        }
    }

    /**
     * peticion GET para el buscador sin relaciones
     */

    static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
       
        $linkToArray = explode(",", $linkTo);
        $searchToArray = explode("_", $search);
        $linkToText = "";
        if (count($linkToArray) > 1) {
            foreach ($linkToArray as $key => $value) {
                if ($key > 0) {
                    $linkToText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }
       
        /**
         * SIN ORDENAR Y LIMITAR DATOS
         */
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText";

        /**
         * ORDENAR DATOS SIN LIMITES
         */
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
        }

        /**
         * ORDEMAR Y LIMITAR DATOS 
         */

        if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        /**
         * LIMITAR DATOS SIN ORDENAR
         */

        if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText LIMIT $startAt, $endAt";
        }


        $stmt = Connection::connect()->prepare($sql);
        foreach ($linkToArray as $key => $value) {
            if ($key > 0)
            {
                $stmt->bindParam(":" . $value, $searchToArray[$key], PDO::PARAM_STR);
            }
            
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * PETICIONES GET PARA EL BUSCADOR ENTRE TABLAS RELACIONADAS
     */

    static public function getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt)
    {

        /**
         * Organizamos los filtros
         */
        $linkToArray = explode(",", $linkTo);
        $searchToArray = explode("_", $search);
        $linkToText = "";
        if (count($linkToArray) > 1) {
            foreach ($linkToArray as $key => $value) {
                if ($key > 0) {
                    $linkToText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }

        /**
         * Organizamos las relaciones
         */

        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);

        $innerJoinText = "";
        if (count($relArray) > 1) {
            foreach ($relArray as $key => $value) {
                if ($key > 0) {
                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".".$typeArray[0]." = ".$value.".".$typeArray[$key]." ";
                }
            }


            /**
             * SIN ORDENAR Y SIN LIMITAR DATOS
             */
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText";

            /**
             * ORDENAR DATOS SIN LIMITES
             */
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
            }

            /**
             * ORDEMAR Y LIMITAR DATOS 
             */

            if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }

            /**
             * LIMITAR DATOS SIN ORDENAR
             */

            if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText LIMIT $startAt, $endAt";
            }


            $stmt = Connection::connect()->prepare($sql);
            foreach ($linkToArray as $key => $value) {
                if($key > 0){
                    $stmt->bindParam(":" . $value, $searchToArray[$key], PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } else {
            return null;
        }
    }

    /**
     * Peticiones GET para seleccion de rangos
    */

    static public function getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo)
    {

        $filter = "";

        if($filterTo != null && $inTo != null){
            $filter = 'AND '.$filterTo.' IN ('.$inTo.')';
        }

        /**
         * SIN ORDENAR Y LIMITAR DATOS
         */
        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter";

        /**
         * ORDENAR DATOS SIN LIMITES
         */
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode";
        }

        /**
         * ORDEMAR Y LIMITAR DATOS 
         */

        if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        /**
         * LIMITAR DATOS SIN ORDENAR
         */

        if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter LIMIT $startAt, $endAt";
        }


        $stmt = Connection::connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Peticiones GET para seleccion de rangos con relaciones
    */

    static public function getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo)
    {

        $filter = "";

        if($filterTo != null && $inTo != null){
            $filter = ' AND '.$filterTo.' IN ('.$inTo.')';
        }

        $relArray = explode(",", $rel);
        $typeArray = explode(",", $type);

        $innerJoinText = "";
        if (count($relArray) > 1) {
            foreach ($relArray as $key => $value) {
                if ($key > 0) {
                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".".$typeArray[0]." = ".$value.".".$typeArray[$key]." ";
                }
            }

            /**
             * SIN ORDENAR Y LIMITAR DATOS
             */
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter";

            /**
             * ORDENAR DATOS SIN LIMITES
             */
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode";
            }

            /**
             * ORDEMAR Y LIMITAR DATOS 
             */

            if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }

            /**
             * LIMITAR DATOS SIN ORDENAR
             */

            if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter LIMIT $startAt, $endAt";
            }


            $stmt = Connection::connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        }else{
            return null;
        }
    }
}
