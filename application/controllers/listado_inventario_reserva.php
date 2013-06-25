<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - listado_inventario_reserva
*/

class listado_inventario_reserva extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('listado_inventario_reserva_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 8 - Usuario Reserva
        if(!$this->perms->verificoPerfil8()) {
            die($this->mensajes->sinPermisos());
        }       
    }
    
    function index() {
        
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("listado_inventario_reserva_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['modelo']) && isset($_POST['nro_serie']) && isset($_POST['marca'])
                && isset($_POST['calibre']) ) { 
            
            $condicion = "";
            $and = 1;
 
            if(!empty($_POST['modelo'])){
                $condicion .= " AND ";
                $aux = "%".$_POST['modelo']."%";
                $condicion .= " u.modelo LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['nro_serie'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['nro_serie']."%";
                $condicion .= " u.nro_serie LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['marca'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['marca']."%";
                $condicion .= " u.marca LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['calibre'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['calibre']."%";
                $condicion .= " u.calibre LIKE ".$this->db->escape($aux);
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
            $order = "deposito";
        }
        //Fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->listado_inventario_reserva_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+5) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        

            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_serie;  0
            $result[] = $row->marca;      1
            $result[] = $row->calibre;    2
            $result[] = $row->modelo;     3
            $result[] = $row->deposito;   4 
            */
            
            if($result[$i+4] == "") {
                $result[$i+4] = "SIN DEPOSITO";
            }
            
            $concat .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("listado_inventario_reserva/consulta");
        $config['total_rows'] = $this->listado_inventario_reserva_model->cantidadRegistros($condicion);
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
            $order = "deposito";
        }
        //Fin verifico order
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = "";
        }
               
        if( empty($de_pagina) || empty($a_pagina) ){            
            echo "La pagina inicial y final deben de estar completadas ";
        }else if( $a_pagina < $de_pagina ){
            echo "La pagina inicila no puede ser mayor que la pagina final verifique";
        }else if( $this->listado_inventario_reserva_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->listado_inventario_reserva_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->listado_inventario_reserva_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th> Nro serie    </th>
                <th> Marca        </th>
                <th> Calibre      </th>
                <th> Modelo       </th>
                <th> Deposito     </th>
            </tr>   
        ';
        
        for($i=0;$i<count($result);$i=$i+5) {         
            
            if($result[$i+4] == "") {
                $result[$i+4] = "SIN DEPOSITO";
            }               
            
            $concat .= "
                <tr>
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
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
            
            case 1:
                $_SESSION['order'][0] = 'nro_serie';
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
                $_SESSION['order'][0] = 'deposito';
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
