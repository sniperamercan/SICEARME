<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - mb_acciones_ordenes_trabajo
*/

class mb_acciones_ordenes_trabajo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_acciones_ordenes_trabajo_model');
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
        $_SESSION['volver'] = "";
        $this->load->view("mb_acciones_ordenes_trabajo_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['nro_accion']) && isset($_POST['nro_orden']) && isset($_POST['seccion'])
                && isset($_POST['tipo_accion']) && isset($_POST['fecha1']) && isset($_POST['fecha2']) ) { 
            
            $condicion = "";
            $and = 1;
 
            if(!empty($_POST['nro_accion'])){
                $condicion .= " AND ";
                $aux = "%".$_POST['nro_accion']."%";
                $condicion .= " d.nro_accion LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['nro_orden'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_orden']."%";
                $condicion .= " d.nro_orden LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['seccion'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['seccion']."%";
                $condicion .= " d.seccion LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['tipo_accion'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['tipo_accion']."%";
                $condicion .= " d.tipo_accion LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }     
            
            if(!empty($_POST['fecha1']) && !empty($_POST['fecha2'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $condicion .= " d.fecha BETWEEN ".$this->db->escape($_POST['fecha1'])." AND ".$this->db->escape($_POST['fecha2']);
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
        
        $result = $this->mb_acciones_ordenes_trabajo_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        $nro_orden_anterior = 0;
        
        for($i=0;$i<count($result);$i=$i+5) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_accion = '"'.$result[$i].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_accion;
            $result[] = $row->nro_orden;
            $result[] = $row->fecha;
            $result[] = $row->seccion;
            $result[] = $row->tipo_accion;       
            */
            
            switch($result[$i+4]) {

                case 0: //accion simple
                    $tipo_accion = "accion simple";
                    break;

                case 1: //accion piezas secundarias
                    $tipo_accion = "accion piezas secundarias";
                    break;

                case 2: //accion piezas asociadas
                    $tipo_accion = "accion piezas asociadas";
                    break;
            }      
            
            if($nro_orden_anterior != $result[$i+1] && $nro_orden_anterior != 0) {
                $concat .=  '<tr> <td colspan="8" style="background-color:#8C8C8C;"> <div id="paging"> <br /> </div> </td> </tr>';
            }
            
            $concat .= "
                <tr class='".$class."'> 
                    <td style='text-align: center; font-weight: bold; color: black;'> ".$result[$i+1]." </td>
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$tipo_accion." </td>
                    <td onclick='verInformacion(".$aux_nro_accion.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='editarAccion(".$aux_nro_accion.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/edit.png' /> </td>
                    <td onclick='eliminarAccion(".$aux_nro_accion.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/delete.gif' /> </td>
                </tr>
            ";
            
            $j++;
            
            $nro_orden_anterior = $result[$i+1];
        }                  
        
        $config['base_url'] = site_url("mb_acciones_ordenes_trabajo/consulta");
        $config['total_rows'] = $this->mb_acciones_ordenes_trabajo_model->cantidadRegistros($condicion);
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
        }else if( $this->mb_acciones_ordenes_trabajo_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->mb_acciones_ordenes_trabajo_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->mb_acciones_ordenes_trabajo_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th> Nro accion   </th>
                <th> Nro orden    </th>
                <th> Fecha        </th>
                <th> Seccion      </th>
                <th> Tipo accion  </th>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+5) {            
            $concat .= "
                <tr>
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td style='text-align: center;'> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
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
                $_SESSION['order'][0] = 'nro_accion';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'nro_orden';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'fecha';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'seccion';
                break;
            
            case 4:
                $_SESSION['order'][0] = 'tipo_accion';
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
    
    function verInformacion() {
        
        $nro_accion = $_POST['nro_accion'];

        $tipo_accion = $this->mb_acciones_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
        switch($tipo_accion) {
            
            case 0: //accion simple
                $concat = $this->verInformacionAccionSimple($nro_accion);
                break;
            
            case 1: //accion piezas secundarias
                $concat = $this->verInformacionAccionSecundaria($nro_accion);
                break;
            
            case 2: //accion piezas asociadas
                $concat = $this->verInformacionAccionAsociada($nro_accion);
                break;
        }
        
        echo $concat;
    }
    
    function verInformacionAccionSimple($nro_accion) {
        
        //obtengo informacion de la accion
        $datos = $this->mb_acciones_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
         *  $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */
        
        $concat = "<p style='font-weight: bold;'> Detalle de la accion Nro - ".$nro_accion." Nro de orden - ".$datos[0]." </p>";

        $concat .= "<div class='datagrid'><table><thead><th> Fecha </th><th> Seccion </th></thead>";  
        
        $j = 0;

        for($i=0; $i<count($datos); $i=$i+5) {
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            } 
            $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$datos[$i+1]."</td> <td>".$datos[$i+2]."</td> </tr></tbody>";
            $j++;
        }

        $concat .= "</table>";

        $concat .= "</div>";
        
        $concat .= "<p style='font-weight: bold;'> Detalles </p>";
        
        $concat .= "<div class='datagrid'><table><thead><th> Detalles </th></thead>";  
        
        $concat .= "<tbody><tr> <td style='text-align: center;'>".$datos[3]."</td> </tr></tbody>";
        
        $concat .= "</table>";

        $concat .= "</div>"; 
        
        return $concat;
    }
    
    function verInformacionAccionSecundaria($nro_accion) {
        
        //obtengo informacion de la accion
        $datos = $this->mb_acciones_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->mb_acciones_ordenes_trabajo_model->verInformacionAccionSecundaria($nro_orden, $nro_accion);
        
        /*
            $retorno[] = $row->nro_cambio;    0
            $retorno[] = $row->nro_parte;     1
            $retorno[] = $row->nombre_parte;  2
            $retorno[] = $row->cantidad;      3
         */
        
        $concat = $this->verInformacionAccionSimple($nro_accion);
        
        $concat .= "<p style='font-weight: bold;'> Detalles de repuestos usados </p>";

        $concat .= "<div class='datagrid'><table><thead><th> Nro cambio </th><th> Nro parte </th><th> Nombre parte </th> <th> Cantidad </th></thead>";  
        
        $j = 0;

        for($i=0; $i<count($datos); $i=$i+4) {
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            } 
            $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$datos[$i]."</td> <td>".$datos[$i+1]."</td> <td>".$datos[$i+2]."</td> <td>".$datos[$i+3]."</td> </tr></tbody>";
            $j++;
        }

        $concat .= "</table>";

        $concat .= "</div>";        
        
        return $concat;
    }
    
    function verInformacionAccionAsociada($nro_accion) {
        
        //obtengo informacion de la accion
        $datos = $this->mb_acciones_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->mb_acciones_ordenes_trabajo_model->verInformacionAccionAsociada($nro_orden, $nro_accion);
        
        /*
            $retorno[] = $row->nro_cambio;          0
            $retorno[] = $row->nro_pieza_anterior;  1
            $retorno[] = $row->nro_pieza_nueva;     2
            $retorno[] = $row->nro_parte;           3
            $retorno[] = $row->nombre_parte;        4
         */
        
        $concat = $this->verInformacionAccionSimple($nro_accion);
        
        $concat .= "<p style='font-weight: bold;'> Detalles de piezas cambiadas al armamento </p>";

        $concat .= "<div class='datagrid'><table><thead><th> Nro cambio </th><th> Pieza anterior </th><th> Pieza nueva </th> <th> Nro parte </th> <th> Nombre parte </th> </thead>";  
        
        $j = 0;

        for($i=0; $i<count($datos); $i=$i+5) {
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            } 
            $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$datos[$i]."</td> <td style='text-align: center;'>".$datos[$i+1]."</td> <td style='text-align: center;'>".$datos[$i+2]."</td> <td>".$datos[$i+3]."</td> <td>".$datos[$i+4]."</td> </tr></tbody>";
            $j++;
        }

        $concat .= "</table>";

        $concat .= "</div>";        
        
        return $concat;        
        
    }
    
    function eliminarAccion() {
        
        $nro_accion = $_POST['nro_accion'];

        $tipo_accion = $this->mb_acciones_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
        switch($tipo_accion) {
            
            case 0: //accion simple
                $this->mb_acciones_ordenes_trabajo_model->eliminarAccionSimple($nro_accion);
                echo "Accion simple Nro - ".$nro_accion." anulada correctamente";
                break;
            
            case 1: //accion piezas secundarias
                $this->mb_acciones_ordenes_trabajo_model->eliminarAccionSecundaria($nro_accion);
                echo "Accion piezas secundarias Nro - ".$nro_accion." anulada correctamente";
                break;
            
            case 2: //accion piezas asociadas
                if(!$this->mb_acciones_ordenes_trabajo_model->hayAcciones($nro_accion)) {
                    $this->mb_acciones_ordenes_trabajo_model->eliminarAccionAsociada($nro_accion);
                    echo "Accion piezas asociadas Nro - ".$nro_accion." anulada correctamente";
                }else{
                    echo "ERROR: La accion tiene varios cambios de pieza usted debe ingresar a editar la accion y borrar uno a uno los cambios de pieza";
                }
                break;
        }
    }
    
    function editarAccion() {
        $_SESSION['editar_nro_accion'] = $_POST['nro_accion'];
        $nro_accion = $_SESSION['editar_nro_accion'];
        
        $tipo_accion = $this->mb_acciones_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
        $_SESSION['volver'] = 1;
        
        echo $tipo_accion;
    }
}

?>
