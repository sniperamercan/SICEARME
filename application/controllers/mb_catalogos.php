<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - mb_catalogos
*/

class mb_catalogos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_catalogos_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 2 y 3 - Usuarios O.C.I y Administradores O.C.I 
        if(!$this->perms->verificoPerfil2() && !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {
        
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        
        $_SESSION['nro_catalogo'] = ""; //para el editar
        
        $this->load->view("mb_catalogos_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['tipo_arma']) && isset($_POST['marca']) && isset($_POST['calibre'])
                && isset($_POST['modelo']) && isset($_POST['empresa']) && isset($_POST['pais_origen']) ) { 
            
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
            
            if(!empty($_POST['empresa'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['empresa']."%";
                $condicion .= " empresa LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }             
            
            if(!empty($_POST['pais_origen'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['pais_origen']."%";
                $condicion .= " pais_origen LIKE ".$this->db->escape($aux);
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
        
        $result = $this->mb_catalogos_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+10) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_interno = '"'.$result[$i].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_interno;
            $result[] = $row->tipo_arma;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->sistema;
            $result[] = $row->año_fabricacion;
            $result[] = $row->empresa;
            $result[] = $row->pais_origen;
            $result[] = $row->vencimiento;            
            */
            $concat .= "
                <tr class='".$class."'> 
                    <td  style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td style='text-align: center;'> ".$result[$i+6]." </td>
                    <td> ".$result[$i+7]." </td>
                    <td> ".$result[$i+8]." </td>
                    <td style='text-align: center;'> ".$result[$i+9]." </td> 
                    <td onclick='editarCatalogo(".$aux_nro_interno.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/edit.png' /> </td>
                    <td onclick='eliminarCatalogo(".$aux_nro_interno.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/delete.gif' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("mb_catalogos/consulta");
        $config['total_rows'] = $this->mb_catalogos_model->cantidadRegistros($condicion);
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
        }else if( $this->mb_catalogos_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->mb_catalogos_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->mb_catalogos_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th onclick="orderBy(0)"> Nro interno </th>
                <th onclick="orderBy(1)"> Tipo arma   </th>
                <th onclick="orderBy(2)"> Marca       </th>
                <th onclick="orderBy(3)"> Calibre     </th>
                <th onclick="orderBy(4)"> Modelo      </th>
                <th onclick="orderBy(5)"> Sistema     </th>
                <th onclick="orderBy(6)"> Fabricacion </th>
                <th onclick="orderBy(7)"> Empresa     </th>
                <th onclick="orderBy(8)"> Origen      </th>
                <th onclick="orderBy(9)"> Vencimiento </th>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+10) {            
            $concat .= "
                <tr>
                    <td  style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td style='text-align: center;'> ".$result[$i+6]." </td>
                    <td> ".$result[$i+7]." </td>
                    <td> ".$result[$i+8]." </td>
                    <td style='text-align: center;'> ".$result[$i+9]." </td> 
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
            
            case 5:
                $_SESSION['order'][0] = 'sistema';
                break;   
            
            case 6:
                $_SESSION['order'][0] = 'año_fabricacion';
                break;
            
            case 7:
                $_SESSION['order'][0] = 'empresa';
                break;
            
            case 8:
                $_SESSION['order'][0] = 'pais_origen';
                break;
            
            case 9:
                $_SESSION['order'][0] = 'vencimiento';
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
    
    function editarCatalogo() {
        
        $_SESSION['nro_catalogo'] = $_POST['nro_catalogo'];
    }
    
    function eliminarCatalogo() {
        
        $nro_catalogo = $_POST['nro_catalogo'];
        
        if(!$this->mb_catalogos_model->catalogoAsociado($nro_catalogo)) {
            $this->mb_catalogos_model->eliminarCatalogo($nro_catalogo);
            echo 1;
        }else {
            echo 0;
        }
    }
}

?>
