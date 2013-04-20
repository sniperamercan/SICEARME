<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - busqueda_repuestos_nro_pieza
*/

class busqueda_repuestos_nro_pieza extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('busqueda_repuestos_nro_pieza_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $_SESSION['seleccion_busqueda'] = ""; //elemento que se selecciona
        $_SESSION['seleccion_busqueda1'] = ""; //elemento que se selecciona
        $_SESSION['seleccion_busqueda2'] = ""; //elemento que se selecciona
        $_SESSION['seleccion_busqueda3'] = ""; //elemento que se selecciona
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("busqueda_repuestos_nro_pieza_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['nro_pieza']) && isset($_POST['nro_parte']) && isset($_POST['nombre_parte']) && isset($_POST['nro_catalogo']) ) { 
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['nro_pieza'])){
                $aux = $_POST['nro_pieza'];
                $condicion .= " nro_pieza LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['nro_parte'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_parte']."%";
                $condicion .= " nro_parte LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['nombre_parte'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nombre_parte']."%";
                $condicion .= " nombre_parte LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['nro_catalogo'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_catalogo']."%";
                $condicion .= " nro_catalogo LIKE ".$this->db->escape($aux);
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
            $order = "nro_pieza";
        }
        //Fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->busqueda_repuestos_nro_pieza_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+8) {
            
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
                    <td> ".$result[$i+5]." </td>
                    <td> ".$result[$i+6]." </td>
                    <td> ".$result[$i+7]." </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("busqueda_repuestos_nro_pieza/consulta");
        $config['total_rows'] = $this->busqueda_repuestos_nro_pieza_model->cantidadRegistros($condicion);
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
            $order = "nro_pieza";
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
        }else if( $this->busqueda_repuestos_nro_pieza_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->busqueda_repuestos_nro_pieza_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->busqueda_repuestos_nro_pieza_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th> Nro pieza    </th>
                <th> Nro parte    </th>
                <th> Nombre parte </th>
                <th> Nro catalogo </th>
                <th> Tipo arma    </th>
                <th> Marca        </th>
                <th> Calibre      </th>
                <th> Modelo       </th>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+8) {            
            $concat .= "
                <tr>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i]."   </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+1]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+2]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+3]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+4]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+5]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+6]." </td>
                    <td style='background-color: #F5ECCE; color: black; text-align: left; font-size: 12px;'> ".$result[$i+7]." </td>    
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
                $_SESSION['order'][0] = 'nro_pieza';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'nro_parte';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'nombre_parte';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'nro_catalogo';
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
        
        $_SESSION['seleccion_busqueda']  = $_POST['nro_pieza'];
        $_SESSION['seleccion_busqueda1'] = $_POST['nro_catalogo'];
        $_SESSION['seleccion_busqueda2'] = $_POST['nro_parte'];
        $_SESSION['seleccion_busqueda3'] = $_POST['nombre_parte'];
        
        /*al seleccionar un catalogo del listado usar esta variable de sesion 
        *   $_SESSION['seleccion_busqueda']
        */
    }    
    
}

?>