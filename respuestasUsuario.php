<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of respuestasUsuario
 *
 * @author tino
 */
require 'Database.php';

  

    
//if ($_SERVER['REQUEST_METHOD'] == 'GET') 
if (isset($_GET['funcion'])) {
    $funcion = $_GET['funcion'];
} 
switch ($funcion) {
    case "getOfertas":
        getOfertas();
        break;
}

function getOfertas(){   
    // Manejar petición GET
    $db= new Database();
    $ofertas= $db->getOfertas(); 
    if ($ofertas) {

        $datos["estado"] = 1;
        $datos["ofertas"] = $ofertas;

        print json_encode($datos);
       
    }
    else 
        {
        print json_encode(array(
            "estado" => 2,
            "mensaje" => "Ha ocurrido un error"
        ));
    }
}


