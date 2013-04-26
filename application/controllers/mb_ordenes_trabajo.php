<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - mb_ordenes_trabajo
*/

class mb_ordenes_trabajo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_ordenes_trabajo_model');
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
        $this->load->view("mb_ordenes_trabajo_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['nro_orden']) && isset($_POST['nro_serie']) && isset($_POST['marca'])
                && isset($_POST['calibre']) && isset($_POST['fecha1']) && isset($_POST['fecha2']) ) { 
            
            $condicion = "";
            $and = 1;
 
            if(!empty($_POST['nro_orden'])){
                $condicion .= " AND ";
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
            
            if(!empty($_POST['fecha1']) && !empty($_POST['fecha2'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $condicion .= " fecha BETWEEN ".$this->db->escape($_POST['fecha1'])." AND ".$this->db->escape($_POST['fecha2']);
                $and = 1; //agrego AND en proximo filtro
            }             
            
            $_SESSION['condicion'] = $condicion;
        }
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = "";
        }
        //Fin, armo condiciones where para sql
        
        //Verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_orden";
        }
        //Fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->mb_ordenes_trabajo_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+8) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_orden = '"'.$result[$i].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_orden;
            $result[] = $row->fecha;
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nombreunidad;
            $result[] = $row->estado_orden_trabajo;        
            */
            $concat .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td> ".$result[$i+6]." </td>
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
                    <td onclick='verObservaciones(".$aux_nro_orden.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='cambiarEstado(".$aux_nro_orden.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/refresh.png' /> </td>
                    <td onclick='editarOrden(".$aux_nro_orden.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/edit.png' /> </td>
                    <td onclick='eliminarOrden(".$aux_nro_orden.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/delete.gif' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("mb_ordenes_trabajo/consulta");
        $config['total_rows'] = $this->mb_ordenes_trabajo_model->cantidadRegistros($condicion);
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
            $order = "nro_orden";
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
        }else if( $this->mb_ordenes_trabajo_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->mb_ordenes_trabajo_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->mb_ordenes_trabajo_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th> Nro orden    </th>
                <th> Fecha        </th>
                <th> Nro serie    </th>
                <th> Marca        </th>
                <th> Calibre      </th>
                <th> Modelo       </th>
                <th> Unidad       </th>
                <th> Estado orden </th>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+8) {            
            $concat .= "
                <tr>
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td> ".$result[$i+6]." </td>
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
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
                $_SESSION['order'][0] = 'nro_orden';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'fecha';
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
                $_SESSION['order'][0] = 'estado_orden_trabajo';
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
    
    function verObservaciones() {
        
        $nro_orden = $_POST['nro_orden'];
        
        $observaciones = $this->mb_ordenes_trabajo_model->verObservaciones($nro_orden);
        $concat = "<p style='font-weight: bold;'> Detalles de nro de orden - ".$nro_orden." </p>";
        $concat .= "<div class='datagrid'><table><thead><th> Observaciones </th></thead>";
        $concat .= "<tbody><tr> <td>".$observaciones."</td> </tr></tbody>";
        $concat .= "</table></div>";

        echo $concat;
    } 
    
    function editarOrdenTrabajo() {
        
        $nro_orden = $_POST['nro_orden'];
        
        if(!$this->mb_ordenes_trabajo_model->hayAcciones($nro_orden)) {
            $_SESSION['nro_orden'] = $_POST['nro_orden'];
            echo 1;
        }else {
            echo 0;
        }     
    }
    
    function eliminarOrdenTrabajo() {
        
        $nro_orden = $_POST['nro_orden'];
        
        if(!$this->mb_ordenes_trabajo_model->hayAcciones($nro_orden)) {
            $this->mb_ordenes_trabajo_model->eliminarOrdenTrabajo($nro_orden);
            echo 1;
        }else {
            echo 0;
        }
    }   
    
    function cambiarEstadoOrdenTrabajo() {
         $_SESSION['nro_orden'] = $_POST['nro_orden'];
         echo 1;
    }
}

?>
