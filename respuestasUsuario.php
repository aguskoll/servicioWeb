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
require 'Database2.php';

 //http://localhost:85/webServiceAndroid2/respuestasUsuario.php?funcion=getOfertas 

    
//if ($_SERVER['REQUEST_METHOD'] == 'GET') 
if (isset($_GET['funcion'])) {
    $funcion = $_GET['funcion'];
    echo '<html> funcion es '.$funcion.' </html>';
} 

switch ($funcion) {
    case "getOfertas":
        getOfertas();
        break;
    case "getOfertaCodigo":
       getOfertaCodigo();
        break;
    case "getOfertasUbicacion":
       getOfertaUbicacion();
        break;
    case "getOfertasDeporte":
       getOfertasDeporte();
        break;
    case "reservarOferta":
        reservarOferta();
        break;
    
}

function getOfertas(){   
    // Manejar petición GET
    $db= new Database();
    $ofertas= $db->getOfertas(); 
    armarJson($ofertas, "ofertas");
}
    /**
    retorna una oferta parseada en json, cuyo codigo es $codigo
     *     
     *  */
    function  getOfertaCodigo(){   
        
        if (isset($_GET['codigo'])) {
             $codigo = $_GET['codigo'];
        }
        else{
            echo '<html> no hay codigo </html>';
        }
     
    
       $db= new Database();
       $oferta= $db->getOfertaCodigo($codigo); 
       armarJson($oferta,"oferta");
    
    }
    
 function getOfertaUbicacion($ubicacion){
    if (isset($_GET['ubicacion'])) {
             $ubicacion = $_GET['ubicacion'];
        }
        else{
            echo '<html> no hay ubicacion </html>';
        }
       $db= new Database();
       $ofertas= $db->getOfertaUbicacion($ubicacion); 
       armarJson($ofertas,"ofertas");
    
    
}
 function getOfertasDeporte(){
      if (isset($_GET['deporte'])) {
             $deporte = $_GET['deporte'];
        }
        else{
            echo '<html> no hay deporte </html>';
        }
       $db= new Database();
       $ofertas= $db->getOfertasDeporte($deporte); 
       armarJson($ofertas,"ofertas");
 }
 /*
  * http://localhost:85/webServiceAndroid2/respuestasUsuario.php?funcion=reservarOferta&codigo=123&idUser=1
 */
  function reservarOferta(){
      
      
      if (isset($_GET['codigo'])) {
             $codigo = $_GET['codigo'];
        }
        else{
            echo '<html> no hay codgigo </html>';
        }
      if (isset($_GET['idUser'])) {
             $idUser = $_GET['idUser'];
        }
        else{
            echo '<html> no hay idUser </html>';
        }
       $db= new Database();
       
       if($db->reservarOferta($codigo, $idUser))
        {
                $datos["estado"] = 1;
                $datos['ok'] = 'true';
                print json_encode($datos);
        }
       else
           {
            $datos["estado"] = 0;
            $datos['ok'] = 'false';
            print json_encode($datos);
       }
       
       
        
  }
   /**
    retorna el json
    * recibe el mensaje a mandar y el tipo de dato que es 
    *     */
 function armarJson($mensaje,$dato){
    
     if ($mensaje) 
    {

        $datos["estado"] = 1;
        $datos[$dato] = $mensaje;

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
