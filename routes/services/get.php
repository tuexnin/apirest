<?php
require_once "controllers/get.controller.php";

$select = $_GET["select"] ?? "*";
$orderBy = $_GET["orderBy"] ?? null;
$orderMode = $_GET["orderMode"] ?? null;
$startAt = $_GET["startAt"] ?? null;
$endAt = $_GET["endAt"] ?? null;
$filterTo = $_GET["filterTo"] ?? null;
$inTo = $_GET["inTo"] ?? null;

$response = new GetController();



if (isset($_GET["linkTo"]) && isset($_GET["equalTo"]) && !isset($_GET["rel"]) && !isset($_GET["type"])) {
    /**
     * peticiones GET con filtro
     */
    $response->getDataFilter($table, $select, $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);

} else if (isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && !isset($_GET["linkTo"]) && !isset($_GET["equalTo"])) {
    /**
     * PETICIONES GET SIN FILTRO ENTRE TABLAS RELACIONADAS
     */

    $response->getRelData($_GET["rel"], $_GET["type"], $select, $orderBy, $orderMode, $startAt, $endAt);

} else if (isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])) {
    /**
     * PETICIONES GET CON FILTRO ENTRE TABLAS RELACIONADAS
     */

    $response->getRelDataFilter($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);
    
} else if (!isset($_GET["rel"]) && !isset($_GET["type"]) && isset($_GET["linkTo"]) && isset($_GET["search"])){

    /**
     * PETICIONES GET PARA EL BUSCADOR SIN RELACIONES
     */

    $response->getDataSearch($table, $select, $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);

}else if (isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["search"])){

    /**
     * PETICIONES GET PARA EL BUSCADOR CON RELACIONES
     */

    $response->getRelDataSearch($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);

}else if (!isset($_GET["rel"]) && !isset($_GET["type"]) && isset($_GET["linkTo"]) && isset($_GET["between1"]) && isset($_GET["between2"])){

    /**
     * PETICIONES GET PARA SELECCION DE RANGOS
     */

    $response->getDataRange($table, $select, $_GET["linkTo"], $_GET["between1"], $_GET["between2"], $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);

}else if (isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["between1"]) && isset($_GET["between2"])){

    /**
     * PETICIONES GET PARA SELECCION DE RANGOS CON RELACIONES
     */

    $response->getRelDataRange($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"], $_GET["between1"], $_GET["between2"], $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);

}else {

    /**
     * peticiones GET sin filtro
     */
    $response->getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
}
