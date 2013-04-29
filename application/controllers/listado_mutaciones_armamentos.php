<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - listado_mutaciones_armamentos
*/

class listado_mutaciones_armamentos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('listado_mutaciones_armamentos_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 6 y 7 - Usuario Taller de armamento y Administrador Taller de armamento
        if(!$this->perms->verificoPerfil6() && !$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {
        
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("listado_mutaciones_armamentos_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['nro_mutacion']) && isset($_POST['nro_orden']) && isset($_POST['nro_serie'])
                && isset($_POST['nuevo_nro_serie']) && isset($_POST['fecha1']) && isset($_POST['fecha2']) ) { 
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['nro_mutacion'])){
                $aux = "%".$_POST['nro_mutacion']."%";
                $condicion .= " nro_mutacion LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['nro_orden'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_orden']."%";
                $condicion .= " nro_orden LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['nro_serie'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_serie']."%";
                $condicion .= " nro_serie LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['nuevo_nro_serie'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nuevo_nro_serie']."%";
                $condicion .= " nro_serie_nuevo LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }     
            
            if(!empty($_POST['fecha1']) && !empty($_POST['fecha2'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $condicion .= " fecha_accion BETWEEN ".$this->db->escape($_POST['fecha1'])." AND ".$this->db->escape($_POST['fecha2']);
                $and = 1; //agrego AND en proximo filtro
            }             
            
            $_SESSION['condicion'] = $condicion;
        }
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = "1";
        }
        //Fin, armo condiciones where para sql
        
        //Verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_mutacion";
        }
        //Fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->listado_mutaciones_armamentos_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+9) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_orden = '"'.$result[$i].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_mutacion; 0 
            $result[] = $row->nro_orden;    1
            $result[] = $row->nro_serie;    2
            $result[] = $row->marca;        3
            $result[] = $row->calibre;      4
            $result[] = $row->modelo;       5
            $result[] = $row->nro_serie_nuevo; 6
            $result[] = $row->fecha_accion;    7
            $result[] = $row->seccion;         8
            */
            
            $concat .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td style='text-align: center;'> ".$result[$i+6]." </td>
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
                    <td> ".$result[$i+8]." </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("listado_mutaciones_armamentos/consulta");
        $config['total_rows'] = $this->listado_mutaciones_armamentos_model->cantidadRegistros($condicion);
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
            $order = "nro_mutacion";
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
        }else if( $this->listado_mutaciones_armamentos_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->listado_mutaciones_armamentos_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->listado_mutaciones_armamentos_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th> Nro mutacion    </th>
                <th> Nro orden       </th>
                <th> Nro serie       </th>
                <th> Marca           </th>
                <th> Calibre         </th>
                <th> Modelo          </th>
                <th> Nuevo Nro serie </th>
                <th> Fecha           </th>
                <th> Seccion         </th>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+9) {            
            $concat .= "
                <tr> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td style='text-align: center;'> ".$result[$i+6]." </td>
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
                    <td> ".$result[$i+8]." </td>
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
                $_SESSION['order'][0] = 'nro_mutacion';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'nro_orden';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'nro_serie';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'marca';
                break;
            
            case 4:
                $_SESSION['order'][0] = 'calibre';
                break;        
            
            case 5:
                $_SESSION['order'][0] = 'modelo';
                break;   
            
            case 6:
                $_SESSION['order'][0] = 'nro_serie_nuevo';
                break;
            
            case 7:
                $_SESSION['order'][0] = 'fecha_accion';
                break;
            
            case 8:
                $_SESSION['order'][0] = 'seccion';
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
}

?>
