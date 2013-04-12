<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - busqueda_catalogos
*/

class busqueda_catalogos extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('busqueda_catalogos_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $_SESSION['seleccion_busqueda'] = ""; //elemento que se selecciona
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("busqueda_catalogos_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['tipo_arma']) && isset($_POST['marca']) && isset($_POST['calibre']) && isset($_POST['modelo']) ) { 
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['tipo_arma'])){
                $aux = $_POST['tipo_arma'];
                $condicion .= " tipo_arma LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['marca'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['marca']."%";
                $condicion .= " marca LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['calibre'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['calibre']."%";
                $condicion .= " calibre LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['modelo'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['modelo']."%";
                $condicion .= " modelo LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }            
            
            $_SESSION['condicion'] = $condicion;
        }
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = 1;
        }
        //Fin, armo condiciones where para sql
        
        //Verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_interno";
        }
        //Fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->busqueda_catalogos_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+5) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_rut = '"'.$result[$i].'"';
            
            $concat .= "
                <tr class='".$class."'> 
                    <td onclick='seleccion(".$aux_rut.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/select.png' /> </td>
                    <td> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("busqueda_catalogos/consulta");
        $config['total_rows'] = $this->busqueda_catalogos_model->cantidadRegistros($condicion);
        $config['per_page'] = $cantReg;
        $config['first_link'] = 'Primera';
        $config['last_link'] = 'Ultima';

        $this->pagination->initialize($config);

        $paginado = '<center>';
        
        $paginado .= "<p style='font-size: 13px;'>";
        
        $paginado .= $this->pagination->create_links();
        
        $paginado .= "</p>";
        
        $paginado .= '</center>';
        
        //Retorno de datos json
        $retorno = array();
        $retorno[] = $concat;
        $retorno[] = $paginado;
        
        echo json_encode($retorno);
    }    
    
    function seteoImpresion() {       
        
        $this->load->view("impresion_view");    
    }
    
    function armoImpresion(){   
        
        $de_pagina = $_POST['de_pagina'];
        $a_pagina  = $_POST['a_pagina'];
      
        //Verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_interno";
        }
        //Fin verifico order
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = 1;
        }
               
        if( empty($de_pagina) || empty($a_pagina) ){            
            echo "La pagina inicial y final deben de estar completadas ";
        }else if( $a_pagina < $de_pagina ){
            echo "La pagina inicila no puede ser mayor que la pagina final verifique";
        }else if( $this->busqueda_catalogos_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->busqueda_catalogos_model->cantidadRegistros($condicion) <= 30 ){
                $ini   = 0;
                $param = 30;
                $this->consultaImpresion($condicion, $ini, $param, $order);
            }else{
                if($de_pagina == $a_pagina){
                    $ini   = $de_pagina * 30 - 30; //pagina inicial
                    $param = 30;
                }else{
                    $ini   = $de_pagina * 30 - 30; //pagina inicial
                    $param = ($a_pagina - $de_pagina) * 30 + 30; //cantidad de registros a mostrar
                }
                $this->consultaImpresion($condicion, $ini, $param, $order);
            }
        }    
    }    
    
    function consultaImpresion($condicion, $param, $cantReg, $order) {  
        
        $result = array();
       
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->busqueda_catalogos_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <td style="background-color: #B45F04; color: white; text-align: center; font-size: 12px;"> Nro interno </td>
                <td style="background-color: #B45F04; color: white; text-align: center; font-size: 12px;"> Tipo arma </td>
                <td style="background-color: #B45F04; color: white; text-align: center; font-size: 12px;"> Marca </td>
                <td style="background-color: #B45F04; color: white; text-align: center; font-size: 12px;"> Calibre </td>
                <td style="background-color: #B45F04; color: white; text-align: center; font-size: 12px;"> Modelo </td>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+5) {            
            $concat .= "
                <tr>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i]."   </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+1]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+2]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+3]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+4]." </td>
                </tr>
            ";
        }                  
        
        $concat .= '</table>';     
                
        $concat .= '</center>';
               
        $_SESSION['contenido'] = $concat;                
    } 
    
    function orderBy() {     
        
        $order = $_POST['order'];  
        
        switch($order){
            
            case 0:
                $_SESSION['order'][0] = 'nro_interno';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'tipo_arma';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'marca';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'calibre';
                break;
            
            case 4:
                $_SESSION['order'][0] = 'modelo';
                break;            
        }
       
        if(!isset($_SESSION['order'][1])){
            $_SESSION['order'][1] = "ASC";
        }else if($_SESSION['order'][1] == "DESC"){
            $_SESSION['order'][1] = "ASC";
        }else if($_SESSION['order'][1] == "ASC"){
            $_SESSION['order'][1] = "DESC";
        }        
    }    

    function seteoSeleccion() {
        
        $_SESSION['seleccion_busqueda'] = $_POST['nro_interno'];
        
        /*al seleccionar un catalogo del listado usar esta variable de sesion 
        *   $_SESSION['seleccion_busqueda']
        */
    }    
    
}

?>