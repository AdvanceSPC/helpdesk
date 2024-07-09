<?php

require_once('../config/connection.php');
require_once('../models/TICKET.php');
$ticket = new Ticket();

switch($_GET["op"]){
    case "listar":
        $datos=$ticket->listar($_POST["us_Id"]);
        $datos= Array();
        foreach($datos as $row){
            $sub_array = array();
            $sub_array[] = $row["Ticket"];
            $sub_array[] = $row["Categoria"];
            $sub_array[] = $row["Titulo"];
            $sub_array[] = $row["Cliente"];
            $sub_array[] = $row["Nombre"]. " " . $row["Apellido"];
            $data[] = $sub_array;
        }

        $results = array(
            "sEcho"=>1,
            "iTotalRecords"=>count($datos),
            "iTotalDisplayRecords"=>count($datos),
            "aaData"=>$data);
        echo json_encode($results);

    break;
}

?>