<?php

class consulta_logs_ingresos extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('consulta_logs_ingresos_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 1 - Administradores del sistema
        if(!$this->perms->verificoPerfil1()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->consulta();
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = 1;
        }
        
        //verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "logfecha, loghora";
        }
        //fin verifico order        
        
        $result = array();
        
        if($param == ""){
            $param = 0;
        }            
        
        $concat = "";
        
        $result = $this->consulta_logs_ingresos_model->consulta_db($param, $cantReg, $condicion, $order);
        
        $concat .= '<div class="datagrid">';
        
        $concat .= '<table>';
        
        $concat .= '<thead>';
        
        $concat .= '
            <tr>      
                <th> Usuario </th>
                <th> Fecha   </th>
                <th> Hora    </th>
                <th> IP      </th>
            </tr>   
        ';
        
        $concat .= '</thead>';
        
        $concat .= '<tbody>';
        
        $j=0;
        
        for($i=0;$i<count($result);$i=$i+4) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_rut = '"'.$result[$i].'"';
            
            $concat .= "
                <tr class='".$class."'> 
                    <td> ".$result[$i]."   </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                </tr>
            ";
            
            $j++;
            
        }                  
        
        $concat .= '</tbody>';
        
        $concat .= '
            <tfoot>
                <tr> <td colspan="4"> <div id="paging"> <br /> </div> </td> </tr>
            </tfoot>
        ';
        
        $concat .= '</table>';      
        
        $concat .= '</div>';
        /*
         * solo para paginado
        $config['base_url']   = site_url("consulta_stock_materia_prima/consulta");
        $config['total_rows'] = $this->consulta_stock_materia_prima_model->cantidadRegistros($condicion);
        $config['per_page']   = $cantReg;
        $config['first_link'] = 'Primera';
        $config['last_link']  = 'Ultima';

        $this->pagination->initialize($config);

        $concat .= "<p style='font-size: 13px;'>";
        
        $concat .= $this->pagination->create_links();
        
        $concat .= "</p>";
        */
        
        $data['consulta'] = $concat;
         
        $this->load->view("consulta_logs_ingresos_view", $data);
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
            $order = "logfecha, loghora";
        }
        //fin verifico order
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];      
        }else{
            $condicion = 1;
        }
        
        $de_pagina = 1;
        $a_pagina  = 1;
               
        if( empty($de_pagina) || empty($a_pagina) ){            
            echo "La pagina inicial y final deben de estar completadas ";
        }else if( $a_pagina < $de_pagina ){
            echo "La pagina inicila no puede ser mayor que la pagina final verifique";
        }else if( $this->consulta_logs_ingresos_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->consulta_logs_ingresos_model->cantidadRegistros($condicion) <= 30 ){
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
        
        $result = $this->consulta_logs_ingresos_model->consulta_db($param, $cantReg, $condicion, $order);
        
        $concat .= '<div class="datagrid">';
        
        $concat .= '<table>';
        
        $concat .= '<thead>';
        
        $concat .= '
            <tr>
                <th> Usuario </th>
                <th> Fecha   </th>
                <th> Hora    </th>
                <th> IP      </th>
            </tr>   
        ';
        
        $concat .= '</thead>';
        
        $concat .= '<tbody>';
                
        for($i=0;$i<count($result);$i=$i+4) {            
            $concat .= "
                <tr>
                    <td> ".$result[$i]."   </td>
                    <td> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$result[$i+3]." </td>
                </tr>
            ";
        }                  
        
        $concat .= '</tbody>';
        
        $concat .= '</table>';     
               
        $concat .= '</div>';
        
        $_SESSION['contenido'] = $concat;                
    } 
    
}

?>
