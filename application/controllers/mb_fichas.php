<?php

class mb_fichas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('mb_fichas_model');
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
        
        $_SESSION['datos_ficha'] = array();
        
        $this->load->view("mb_fichas_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //INICIO, ARMO CONDICIONES WHERE PARA SQL
        if( isset($_POST['nro_serie']) && isset($_POST['marca']) && isset($_POST['calibre'])
                && isset($_POST['modelo']) && isset($_POST['nro_compra']) && isset($_POST['nro_catalogo']) ) { 
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['nro_serie']) && $this->form_validation->numeric($_POST['nro_serie'])){
                $condicion .= " nro_serie = ".$this->db->escape($_POST['nro_serie']);
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
            
            if(!empty($_POST['nro_compra']) && $this->form_validation->numeric($_POST['nro_compra']) ){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $condicion .= " nro_interno_compra = ".$this->db->escape($_POST['nro_compra']);
                $and = 1; //agrego AND en proximo filtro
            }  
            
            if(!empty($_POST['nro_catalogo']) && $this->form_validation->numeric($_POST['nro_catalogo']) ){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $condicion .= " nro_interno_catalogo = ".$this->db->escape($_POST['nro_catalogo']);
                $and = 1; //agrego AND en proximo filtro
            }              
            
            $_SESSION['condicion'] = $condicion;
        }
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = 1;
        }
        //FIN, ARMO CONDICIONES WHERE PARA SQL
        
        //verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_serie";
        }
        //fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->mb_fichas_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+8) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_serie = '"'.$result[$i].'"';
            $aux_marca     = '"'.$result[$i+1].'"';
            $aux_calibre   = '"'.$result[$i+2].'"';
            $aux_modelo    = '"'.$result[$i+3].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nro_interno_compra;
            $result[] = $row->nro_interno_catalogo;          
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
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
                    <td onclick='verAccesorios(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='verPiezas(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='editarFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/edit.png' /> </td>
                    <td onclick='eliminarFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/delete.gif' /> </td>    
                </tr>
            ";
            
            $j++;
            
        }                  
        
        $config['base_url'] = site_url("mb_fichas/consulta");
        $config['total_rows'] = $this->mb_fichas_model->cantidadRegistros($condicion);
        $config['per_page'] = $cantReg;
        $config['first_link'] = 'Primera';
        $config['last_link'] = 'Ultima';

        $this->pagination->initialize($config);

        $paginado = '<center>';
        
        $paginado .= "<p style='font-size: 13px;'>";
        
        $paginado .= $this->pagination->create_links();
        
        $paginado .= "</p>";
        
        $paginado .= '</center>';
        
        //retorno de datos json
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
      
        //verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_serie";
        }
        //fin verifico order
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = 1;
        }
               
        if( empty($de_pagina) || empty($a_pagina) ){            
            echo "La pagina inicial y final deben de estar completadas ";
        }else if( $a_pagina < $de_pagina ){
            echo "La pagina inicila no puede ser mayor que la pagina final verifique";
        }else if( $this->mb_fichas_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->mb_fichas_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->mb_fichas_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th> Nro serie    </th>
                <th> Marca        </th>
                <th> Calibre      </th>
                <th> Modelo       </th>
                <th> Tipo         </th>
                <th> Sistema      </th>
                <th> Nro compra   </th>
                <th> Nro catalogo </th>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+8) {            
            $concat .= "
                <tr>
                    <td  style='text-align: center;'> ".$result[$i]." </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td style='text-align: center;'> ".$result[$i+6]." </td>
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
                $_SESSION['order'][0] = 'nro_serie';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'marca';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'calibre';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'modelo';
                break;
            
            case 4:
                $_SESSION['order'][0] = 'tipo_arma';
                break;
            
            case 5:
                $_SESSION['order'][0] = 'sistema';
                break;            
            
            case 6:
                $_SESSION['order'][0] = 'nro_interno_compra';
                break;        
            
            case 7:
                $_SESSION['order'][0] = 'nro_interno_catalogo';
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
    
    function verAccesorios() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        if(!$this->mb_fichas_model->tieneAccesorios($nro_serie, $marca, $calibre, $modelo)) {
            echo "No tiene ningun accesorio asociado";
        }else {
            $accesorios = array();
            $accesorios = $this->mb_fichas_model->verAccesorios($nro_serie, $marca, $calibre, $modelo);
            $concat = "<p style='font-weight: bold;'> Acceorios asociados a la ficha </p><div class='datagrid'><table><thead><th> Nro accesorios </th><th> Tipo accesorio </th><th> Descripcion </th></thead>";
           
            /*
             * retorno del array de catalogos
            $retorno[] = $row->nro_accesorio;
            $retorno[] = $row->tipo_accesorio;
            $retorno[] = $row->descripcion;
            */
            
            $j = 0;
            
            for($i=0; $i<count($accesorios); $i=$i+3) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$accesorios[$i]."</td> <td>".$accesorios[$i+1]."</td> <td>".$accesorios[$i+2]."</td> </tr></tbody>";
                $j++;
            }
            
            $concat .= "</table></div>";
            
            echo $concat;
        }
    } 
    
    function verPiezas() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        if(!$this->mb_fichas_model->tienePiezas($nro_serie, $marca, $calibre, $modelo)) {
            echo "No tiene ninguna pieza asociado";
        }else {
            $piezas = array();
            $piezas = $this->mb_fichas_model->verPiezas($nro_serie, $marca, $calibre, $modelo);
            $concat = "<p style='font-weight: bold;'> Piezas asociados a la ficha </p><div class='datagrid'><table><thead><th> Nro pieza </th><th> Tipo pieza </th><th> Descripcion </th></thead>";
           
            /*
             * retorno del array de catalogos
            $retorno[] = $row->nro_pieza;
            $retorno[] = $row->tipo_pieza;
            $retorno[] = $row->descripcion;
            */
            
            $j = 0;
            
            for($i=0; $i<count($piezas); $i=$i+3) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$piezas[$i]."</td> <td>".$piezas[$i+1]."</td> <td>".$piezas[$i+2]."</td> </tr></tbody>";
                $j++;
            }
            
            $concat .= "</table></div>";
            
            echo $concat;
        }
    }  
    
    function editarFicha() {
        $_SESSION['datos_ficha'][] = $_POST['nro_serie'];
        $_SESSION['datos_ficha'][] = $_POST['marca'];
        $_SESSION['datos_ficha'][] = $_POST['calibre'];
        $_SESSION['datos_ficha'][] = $_POST['modelo'];
    }
    
    function eliminarFicha() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        if($this->mb_fichas_model->existeHistorialFicha($nro_serie, $marca, $calibre, $modelo) == 0) {
            $this->mb_fichas_model->eliminarFicha($nro_serie, $marca, $calibre, $modelo);
            echo 1;
        }else {
            echo 0;
        }        
        
    }
}

?>
