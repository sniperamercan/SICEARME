<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - consulta_actas_unidad
*/

class consulta_actas_unidad extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('consulta_actas_unidad_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 2, 3, 4 y 5 - Usuario O.C.I y Administrador O.C.I, Usuarios Abastecimiento y Administradores Abastecimiento
        if(!$this->perms->verificoPerfil2() && !$this->perms->verificoPerfil3() && !$this->perms->verificoPerfil4() && !$this->perms->verificoPerfil5()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {
        
         //cargo unidades
        $unidades = $this->consulta_actas_unidad_model->cargoUnidades();
        
        $data['unidades'] = "<option value=''> Seleccione... </option>";
        
        for($i=0; $i<count($unidades); $i=$i+2) {
            $data['unidades'] .= "<option value='".$unidades[$i]."'>".$unidades[$i+1]."</option>";
        }
        //fin cargo unidades           
        
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("consulta_actas_unidad_view", $data);
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        $result = array();
        
        //Inicio, armo condiciones where para sql
        if(isset($_POST['unidad'])) { 
            
            $condicion = "";
            $and = 0;
            
            $_SESSION['unidad'] = $_POST['unidad'];
 
            if(!empty($_POST['unidad'])) {
                $condicion .= " unidad_recibe = ".$this->db->escape($_POST['unidad']);
                $and = 1; //agrego AND en proximo filtro
            } 
            
            $_SESSION['condicion'] = $condicion;
        }
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];     
            $correcto = 1;
        }else{
            $condicion = "";
            $correcto = 0;
            $result[] = 0;
            echo json_encode($result);
        }
        //Fin, armo condiciones where para sql
        
        //Verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_acta";
        }
        //Fin verifico order        
        
        if($param == ""){
            $param = 0;
        }
        
        if($correcto) {
            
            //ACTAS ALTA
            
            $concat = "";

            $condicion = " unidad_recibe = ".$this->db->escape($_SESSION['unidad']);
            
            $result = $this->consulta_actas_unidad_model->consulta_db_actas_alta($param, $cantReg, $condicion, $order);

            $j=0;

            $nombreunidad = $this->consulta_actas_unidad_model->nombreUnidad($_SESSION['unidad']);
            $total = $this->consulta_actas_unidad_model->cantidadRegistros_alta($condicion);
            
            $concat .= '
                
                <p class="subtituloform"> ACTAS ALTA </p>

                <p class="subtituloform"> '.$nombreunidad. ' &nbsp;&nbsp;&nbsp;&nbsp; Total actas - '.$total.' </p>
                    
                <table>

                    <thead style="text-align: center; cursor: pointer;">
                        <tr>      
                            <th> Nº acta        </th>
                            <th> Unidad entrega </th>
                            <th> Fecha          </th>
                        </tr>
                    </thead>

            ';   
            
            $concat .= '<tbody>';
            
            for($i=0;$i<count($result);$i=$i+3) {

                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                }                        

                /* 
                 * lo que contiene el array adentro 
                $result[] = $row->nro_acta;
                $result[] = $row->unidad_entrega;
                $result[] = $row->fecha_transaccion;      
                */
            
                $concat .= "
                    <tr class='".$class."'> 
                        <td style='text-align: center;'> ".$result[$i]."   </td>
                        <td> ".$this->consulta_actas_unidad_model->nombreUnidad($result[$i+1])." </td>
                        <td style='text-align: center;'> ".$result[$i+2]." </td>
                    </tr>
                ";

                $j++;
            }                  

            $concat .= '</tbody>';
            
            $concat .= '
                <tfoot>
                    <tr> <td colspan="3"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
            ';      
            
            $concat .= '</table>';
            
            $concat .= '<hr />';
            
            
            //-----------------------------//
            //ACTAS BAJA
            
            $condicion = " unidad_entrega = ".$this->db->escape($_SESSION['unidad']);
          
            $result = $this->consulta_actas_unidad_model->consulta_db_actas_baja($param, $cantReg, $condicion, $order);

            $j=0;

            $nombreunidad = $this->consulta_actas_unidad_model->nombreUnidad($_SESSION['unidad']);
            $total = $this->consulta_actas_unidad_model->cantidadRegistros_baja($condicion);
            
            $concat .= '
                
                <p class="subtituloform"> ACTAS BAJA </p>

                <p class="subtituloform"> '.$nombreunidad. ' &nbsp;&nbsp;&nbsp;&nbsp; Total actas - '.$total.' </p>
                    
                <table>

                    <thead style="text-align: center; cursor: pointer;">
                        <tr>      
                            <th> Nº acta        </th>
                            <th> Unidad recibe  </th>
                            <th> Fecha          </th>
                        </tr>
                    </thead>

            ';   
            
            $concat .= '<tbody>';
            
            for($i=0;$i<count($result);$i=$i+3) {

                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                }                        

                /* 
                 * lo que contiene el array adentro 
                $result[] = $row->nro_acta;
                $result[] = $row->unidad_entrega;
                $result[] = $row->fecha_transaccion;      
                */
            
                $concat .= "
                    <tr class='".$class."'> 
                        <td style='text-align: center;'> ".$result[$i]."   </td>
                        <td> ".$this->consulta_actas_unidad_model->nombreUnidad($result[$i+1])." </td>
                        <td style='text-align: center;'> ".$result[$i+2]." </td>
                    </tr>
                ";

                $j++;
            }                  

            $concat .= '</tbody>';
            
            $concat .= '
                <tfoot>
                    <tr> <td colspan="3"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
            ';      
            
            $concat .= '</table>';          
            
            $config['base_url'] = site_url("consulta_actas_unidad/consulta");
            $config['total_rows'] = $this->consulta_actas_unidad_model->cantidadRegistros_alta($condicion);
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
    }    
    
    function cargoFichasFiltro() {
        
        $nro_serie = $_SESSION['seleccion_busqueda'];
        $marca     = $_SESSION['seleccion_busqueda1'];
        $calibre   = $_SESSION['seleccion_busqueda2'];
        $modelo    = $_SESSION['seleccion_busqueda3'];
        
        //Retorno los datos
        $retorno = array();
        $retorno[] = $nro_serie;
        $retorno[] = $marca;
        $retorno[] = $calibre;
        $retorno[] = $modelo;
        
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
            $order = "nro_acta";
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
        }else if( $this->consulta_actas_unidad_model->cantidadRegistros($condicion) < (($a_pagina * 30) - 30) ){
            echo "No existe tal cantidad de paginas para esa consulta verifique";
        }else{
            echo "1";
            if( $this->consulta_actas_unidad_model->cantidadRegistros($condicion) <= 30 ){
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

        $result = $this->consulta_stock_total_armas_detallado_model->consulta_db($param, $cantReg, $condicion, $order);

        $j=0;

        $nombreunidad = $this->consulta_stock_total_armas_detallado_model->nombreUnidad($_SESSION['unidad']);
        $total = $this->consulta_stock_total_armas_detallado_model->cantidadRegistros($condicion);
                
        
        $concat .= '<center>';
        
            $concat .= '
                <p class="subtituloform"> '.$nombreunidad. ' &nbsp;&nbsp;&nbsp;&nbsp; Total de armas - '.$total.' </p>

                <table>

                    <thead style="text-align: center; cursor: pointer;">
                        <tr>      
                            <th onclick="orderBy(0)"> Nro serie  </th>
                            <th onclick="orderBy(1)"> Marca      </th>
                            <th onclick="orderBy(2)"> Calibre    </th>
                            <th onclick="orderBy(3)"> Modelo     </th>
                            <th> Tipo    </th>
                            <th> Sistema </th>
                        </tr>
                    </thead>

            ';   
            
            $concat .= '<tbody>';
            
            for($i=0;$i<count($result);$i=$i+6) {

                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                }                        

                /* 
                 * lo que contiene el array adentro 
                $result[] = $row->nro_serie;
                $result[] = $row->marca;
                $result[] = $row->calibre;
                $result[] = $row->modelo;       
                */
            
                $concat .= "
                    <tr class='".$class."'> 
                        <td style='text-align: center;'> ".$result[$i]."   </td>
                        <td> ".$result[$i+1]." </td>
                        <td> ".$result[$i+2]." </td>
                        <td> ".$result[$i+3]." </td>
                        <td> ".$result[$i+4]." </td>
                        <td> ".$result[$i+5]." </td>
                    </tr>
                ";

                $j++;
            }                  

            $concat .= '</tbody>';
            
            $concat .= '
                <tfoot>
                    <tr> <td colspan="6"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
            ';      
            
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
                $_SESSION['order'][0] = 'unidad_entrega';
                break;
            
            case 2:
                $_SESSION['order'][0] = 'unidad_recibe';
                break;
            
            case 3:
                $_SESSION['order'][0] = 'fecha_transaccion';
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
