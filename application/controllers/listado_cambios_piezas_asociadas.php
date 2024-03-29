<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - listado_cambios_piezas_asociadas
*/

class listado_cambios_piezas_asociadas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('listado_cambios_piezas_asociadas_model');
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
        $this->load->view("listado_cambios_piezas_asociadas_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['nro_orden']) && isset($_POST['nro_pieza']) && isset($_POST['nro_parte'])
                && isset($_POST['nombre_parte']) ) { 
            
            $condicion = "";
            $and = 1;
 
            if(!empty($_POST['nro_orden'])){
                $condicion .= " AND ";
                $aux = "%".$_POST['nro_orden']."%";
                $condicion .= " c.nro_orden LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['nro_pieza'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_pieza']."%";
                $condicion .= " c.nro_pieza_nueva LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['nro_parte'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_parte']."%";
                $condicion .= " c.nro_parte LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['nombre_parte'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nombre_parte']."%";
                $condicion .= " c.nombre_parte LIKE ".$this->db->escape($aux);
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
        
        $result = $this->listado_cambios_piezas_asociadas_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+10) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_orden = '"'.$result[$i].'"';
            $aux_nro_cambio = '"'.$result[$i+9].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_orden;
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nro_pieza_anterior;
            $result[] = $row->nro_pieza_nueva;
            $result[] = $row->nro_parte;
            $result[] = $row->nombre_parte; 
            $result[] = $row->nro_cambio;      
            */
            
            $concat .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]."   </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td style='text-align: center;'> ".$result[$i+5]." </td>
                    <td style='text-align: center;'> ".$result[$i+6]." </td>
                    <td> ".$result[$i+7]." </td>
                    <td> ".$result[$i+8]." </td>
                    <td onclick='verObservaciones(".$aux_nro_orden.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='imprimir(".$aux_nro_cambio.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='imprimir(".$aux_nro_cambio.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/print.png' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("listado_ordenes_trabajo/consulta");
        $config['total_rows'] = $this->listado_cambios_piezas_asociadas_model->cantidadRegistros($condicion);
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
        //$retorno[] = $paginado;
        
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
        }else if( $this->listado_cambios_piezas_asociadas_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->listado_cambios_piezas_asociadas_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->listado_cambios_piezas_asociadas_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>      
                <th> Nro orden       </th>
                <th> Nro serie       </th>
                <th> Marca           </th>
                <th> Calibre         </th>
                <th> Modelo          </th>
                <th> Pieza ant       </th>
                <th> Pieza nueva     </th>
                <th> Nro parte       </th>
                <th> Nombre parte    </th>
            </tr> 
        ';
                
        for($i=0;$i<count($result);$i=$i+10) {            
            $concat .= "
                <tr>
                    <td style='text-align: center;'> ".$result[$i]."   </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td style='text-align: center;'> ".$result[$i+5]." </td>
                    <td style='text-align: center;'> ".$result[$i+6]." </td>
                    <td> ".$result[$i+7]." </td>
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
                $_SESSION['order'][0] = 'nro_orden';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'nro_pieza_anterior';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'nro_pieza_nueva';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'nro_parte';
                break;
            
            case 4:
                $_SESSION['order'][0] = 'nombre_parte';
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
        
        $observaciones = $this->listado_cambios_piezas_asociadas_model->verObservaciones($nro_orden);
        $concat = "<p style='font-weight: bold;'> Detalles de nro de orden - ".$nro_orden." </p>";
        $concat .= "<div class='datagrid'><table><thead><th> Observaciones </th></thead>";
        $concat .= "<tbody><tr> <td>".$observaciones."</td> </tr></tbody>";
        $concat .= "</table></div>";

        echo $concat;
    } 
    
    function imprimir() {
        $_SESSION['nro_cambio'] = $_POST['nro_cambio'];
    }    
}

?>
