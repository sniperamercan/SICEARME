<?php

class listado_actas_alta extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('listado_actas_alta_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 4 y 5 - Usuarios Abastecimiento y Administradores Abastecimiento
        if(!$this->perms->verificoPerfil4() && !$this->perms->verificoPerfil5()) {
            die($this->mensajes->sinPermisos());
        }      
    }
    
    function index() {
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        
        $_SESSION['nro_acta'] = ""; //para el editar
        
        $this->load->view("listado_actas_alta_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        //INICIO, ARMO CONDICIONES WHERE PARA SQL
        if( isset($_POST['nro_acta']) && isset($_POST['estado']) && isset($_POST['fecha1']) && isset($_POST['fecha2']) ) { 
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['nro_acta']) && $this->form_validation->numeric($_POST['nro_acta'])){
                $condicion .= " nro_acta = ".$this->db->escape($_POST['nro_acta']);
                $and = 1; //agrego AND en proximo filtro
            }          
            
            if(!empty($_POST['estado']) && $this->form_validation->numeric($_POST['estado'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $condicion .= " estado = ".$this->db->escape($_POST['estado']);
                $and = 1; //agrego AND en proximo filtro
            }     
            
            if(!empty($_POST['fecha1']) && !empty($_POST['fecha2'])){
                if($and == 1){
                    $condicion .= " AND ";
                }
                $condicion .= " fecha_transaccion BETWEEN ".$this->db->escape($_POST['fecha1'])." AND ".$this->db->escape($_POST['fecha2']);
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
            $order = "nro_acta";
        }
        //fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->listado_actas_alta_model->consulta_db($param, $cantReg, $condicion, $order);
          
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+8) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_acta = '"'.$result[$i].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_acta;
            $result[] = $row->fecha_transaccion;
            $result[] = $row->unidad_entrega;
            $result[] = $row->unidad_recibe;
            $result[] = $row->representante_sma;
            $result[] = $row->representante_unidad;
            $result[] = $row->representante_supervision;
            $result[] = $row->estado;         
            */
            
            $unidad_entrega = $this->listado_actas_alta_model->nombreUnidad($result[$i+2]);
            $unidad_recibe  = $this->listado_actas_alta_model->nombreUnidad($result[$i+3]);
            
            $concat .= "
                <tr class='".$class."'> 
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$unidad_entrega." </td>
                    <td> ".$unidad_recibe." </td>
                    <td> ".$result[$i+4]." </td>
                    <td> ".$result[$i+5]." </td>
                    <td> ".$result[$i+6]." </td>
                    <td style='text-align: center;'> ".$result[$i+7]." </td>
                    <td onclick='verObservaciones(".$aux_nro_acta.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='verEntrega(".$aux_nro_acta.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='imprimirRecibo(".$aux_nro_acta.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>  
                    <td onclick='imprimirRecibo(".$aux_nro_acta.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/print.png' /> </td>    
                </tr>
            ";
            
            $j++;
            
        }                  
        
        $config['base_url'] = site_url("listado_actas_alta/consulta");
        $config['total_rows'] = $this->listado_actas_alta_model->cantidadRegistros($condicion);
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
            $order = "nro_acta";
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
        }else if( $this->listado_actas_alta_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->listado_actas_alta_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->listado_actas_alta_model->consulta_db($param, $cantReg, $condicion, $order);
                
        
        $concat .= '<center>';
        
        $concat .= '<table style="border: none; width: 50%">';
        
        $concat .= '
            <tr>
                <th onclick="orderBy(0)"> Nro acta             </th>
                <th onclick="orderBy(1)"> Fecha                </th>
                <th onclick="orderBy(2)"> Unidad entrega       </th>
                <th onclick="orderBy(3)"> Unidad recibe        </th>
                <th onclick="orderBy(4)"> Representante SMA    </th>
                <th onclick="orderBy(5)"> Representante unidad </th>
                <th onclick="orderBy(6)"> Supervision          </th>
                <th onclick="orderBy(7)"> Estado               </th>
            </tr>   
        ';
                
        for($i=0;$i<count($result);$i=$i+8) {   
            
            $unidad_entrega = $this->listado_actas_alta_model->nombreUnidad($result[$i+2]);
            $unidad_recibe  = $this->listado_actas_alta_model->nombreUnidad($result[$i+3]);            
            
            $concat .= "
                <tr>
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$unidad_entrega." </td>
                    <td> ".$unidad_recibe." </td>
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
                $_SESSION['order'][0] = 'nro_acta';
                break;
            
            case 1:
                $_SESSION['order'][0] = 'fecha_transaccion';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'unidad_entrega';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'unidad_recibe';
                break;
            
            case 4:
                $_SESSION['order'][0] = 'representante_sma';
                break;        
            
            case 5:
                $_SESSION['order'][0] = 'representante_unidad';
                break;   
            
            case 6:
                $_SESSION['order'][0] = 'representante_supervision';
                break;
            
            case 7:
                $_SESSION['order'][0] = 'estado';
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
        
        $nro_acta = $_POST['nro_acta'];
        
        $observaciones = $this->listado_actas_alta_model->verObservaciones($nro_acta);
        $concat = "<p style='font-weight: bold;'> Nro de acta - ".$nro_acta." </p><div class='datagrid'><table><thead><th> Observaciones </th></thead>";
        $concat .= "<tbody><tr> <td style='text-align: center;'>".$observaciones."</td></tr></tbody>";
        $concat .= "</table></div>";

        echo $concat;
    } 
    
    function verEntregas() {
        
        $nro_acta = $_POST['nro_acta'];
        
        if(!$this->listado_actas_alta_model->tieneFichas($nro_acta)) {
            echo "El nro de acta - ".$nro_acta." no tiene ninguna ficha asociada";
        }else {
            
            $fichas = array();
            $fichas = $this->listado_actas_alta_model->verFichas($nro_acta);
            
            $accesorios = array();
            $accesorios = $this->listado_actas_alta_model->verAccesorios($nro_acta);
            
            $concat = "<p style='font-weight: bold;'> Entregas asociadas al nro de acta - ".$nro_acta." </p>";
            
            $concat .= "<div class='datagrid'><table><thead><th> Nro serie </th><th> Marca </th><th> Calibre </th><th> Modelo </th></thead>";
           
            /*
             * $retorno[] = $row->nro_serie;
             * $retorno[] = $row->marca;
             * $retorno[] = $row->calibre;
             * $retorno[] = $row->modelo;
            */
            
            $j = 0;
            
            for($i=0; $i<count($fichas); $i=$i+4) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$fichas[$i]."</td> <td>".$fichas[$i+1]."</td> <td>".$fichas[$i+2]."</td> <td>".$fichas[$i+3]."</td> </tr></tbody>";
                $j++;
            }
            
            $concat .= "</table>";
              
            $concat .= "</div>";
            
            $concat .= "<br /> <br />";
            
            $concat .= "<div class='datagrid'><table><thead><th> Nro serie </th><th> Marca </th><th> Calibre </th><th> Modelo </th><th> Nro accesorio </th></thead>";
           
            /*
             * $retorno[] = $row->nro_serie;
             * $retorno[] = $row->marca;
             * $retorno[] = $row->calibre;
             * $retorno[] = $row->modelo;
             * $retorno[] = $row->nro_accesorio;
            */
            
            $j = 0;
            
            for($i=0; $i<count($accesorios); $i=$i+5) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$accesorios[$i]."</td> <td>".$accesorios[$i+1]."</td> <td>".$accesorios[$i+2]."</td> <td>".$accesorios[$i+3]."</td> <td>".$accesorios[$i+4]."</td> </tr></tbody>";
                $j++;
            }
            
            $concat .= "</table>";            
            

            $concat .= "</div>";
            
            echo $concat;
        }
    }     
    
    function imprimirRecibo() {
        $_SESSION['nro_acta'] = $_POST['nro_acta'];
    }
}

?>
