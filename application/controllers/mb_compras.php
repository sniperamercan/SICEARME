<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - mb_compras
*/

class mb_compras extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_compras_model');
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
        $this->load->view("mb_compras_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //Inicio, armo condiciones where para sql
        if( isset($_POST['nro_compra']) && isset($_POST['modalidad']) && isset($_POST['empresa'])
                && isset($_POST['empresa']) && isset($_POST['pais_empresa']) && isset($_POST['fecha1']) && isset($_POST['fecha2']) ) { 
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['nro_compra'])){
                $aux = $_POST['nro_compra'];
                $condicion .= " nro_compra LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['modalidad'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['modalidad']."%";
                $condicion .= " modalidad LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['empresa'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['empresa']."%";
                $condicion .= " empresa_proveedora LIKE ".$this->db->escape($aux);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['pais_empresa'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $aux = "%".$_POST['pais_empresa']."%";
                $condicion .= " pais_empresa LIKE ".$this->db->escape($aux);
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
        
        $result = $this->mb_compras_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+9) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_interno = '"'.$result[$i].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_interno;
            $result[] = $row->nro_compra;
            $result[] = $row->fecha;
            $result[] = $row->empresa_proveedora;
            $result[] = $row->pais_empresa;
            $result[] = $row->descripcion;
            $result[] = $row->modalidad;
            $result[] = $row->cantidad_armas;
            $result[] = $row->precio;          
            */
            $concat .= "
                <tr class='".$class."'> 
                    <td  style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td style='text-align: center;'> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td> ".$result[$i+6]." </td>
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
                    <td style='text-align: center;'> ".$result[$i+8]." </td>
                    <td onclick='verCatalogos(".$aux_nro_interno.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='editarCompra(".$aux_nro_interno.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/edit.png' /> </td>
                    <td onclick='eliminarCompra(".$aux_nro_interno.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/delete.gif' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        $config['base_url'] = site_url("mb_compras/consulta");
        $config['total_rows'] = $this->mb_compras_model->cantidadRegistros($condicion);
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
        }else if( $this->mb_compras_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->mb_compras_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->mb_compras_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th onclick="orderBy(0)"> Nro interno     </th>
                <th onclick="orderBy(1)"> Nro compra      </th>
                <th onclick="orderBy(2)"> Fecha           </th>
                <th onclick="orderBy(3)"> Empresa         </th>
                <th onclick="orderBy(4)"> Pais            </th>
                <th onclick="orderBy(5)"> Modalidad       </th>
                <th onclick="orderBy(6)"> Cantidad armas  </th>
                <th onclick="orderBy(7)"> Precio total    </th>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+9) {            
            $concat .= "
                <tr>
                    <td  style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td style='text-align: center;'> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td> ".$result[$i+6]." </td>
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
                    <td style='text-align: center;'> ".$result[$i+8]." </td>
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
                $_SESSION['order'][0] = 'nro_compra';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'fecha';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'empresa_proveedora';
                break;
            
            case 4:
                $_SESSION['order'][0] = 'pais_empresa';
                break;        
            
            case 5:
                $_SESSION['order'][0] = 'descripcion';
                break;   
            
            case 6:
                $_SESSION['order'][0] = 'modalidad';
                break;
            
            case 7:
                $_SESSION['order'][0] = 'cantidad_armas';
                break;
            
            case 8:
                $_SESSION['order'][0] = 'precio';
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
    
    function verCatalogos() {
        
        $nro_interno = $_POST['nro_interno'];
        
        if(!$this->mb_compras_model->tieneCatalogos($nro_interno)) {
            echo "El nro de compra - ".$nro_interno." no tiene ningun catalogo asociado";
        }else {
            $catalogos = array();
            $catalogos = $this->mb_compras_model->verCatalogos($nro_interno);
            $concat = "<p style='font-weight: bold;'> Catalogos asociados a la compra nro - ".$nro_interno." </p><div class='datagrid'><table><thead><th> Nro catalogo </th><th> Tipo arma </th><th> Marca </th><th> Calibre </th><th> Modelo </th><th> Sistema </th><th> Empresa </th><th> Pais </th></thead>";
           
            /*
             * retorno del array de catalogos
            $retorno[] = $row->nro_interno_catalogo;
            $retorno[] = $row->tipo_arma;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
            $retorno[] = $row->sistema;
            $retorno[] = $row->empresa;
            $retorno[] = $row->pais_origen;
            */
            
            $j = 0;
            
            for($i=0; $i<count($catalogos); $i=$i+8) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$catalogos[$i]."</td> <td>".$catalogos[$i+1]."</td> <td>".$catalogos[$i+2]."</td> <td>".$catalogos[$i+3]."</td> <td>".$catalogos[$i+4]."</td> <td>".$catalogos[$i+5]."</td> <td>".$catalogos[$i+6]."</td> <td>".$catalogos[$i+7]."</td> </tr></tbody>";
                $j++;
            }
            
            $concat .= "</table></div>";
            
            echo $concat;
        }
    } 
    
    function editarCompra() {
        
        $_SESSION['nro_compra'] = $_POST['nro_compra'];
    }
    
    function eliminarCompra() {
        
        $nro_compra = $_POST['nro_compra'];
        
        if(!$this->mb_compras_model->fichaAsociada($nro_compra)) {
            $this->mb_compras_model->eliminarCompra($nro_compra);
            echo 1;
        }else {
            echo 0;
        }
    }    
}

?>
