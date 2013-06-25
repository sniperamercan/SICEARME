<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - consulta_historial_movimiento_ficha
*/

class consulta_historial_movimiento_ficha extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('consulta_historial_movimiento_ficha_model');
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
        
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("consulta_historial_movimiento_ficha_view");
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        $result = array();
        
        //Inicio, armo condiciones where para sql
        if(isset($_POST['nro_serie']) && isset($_POST['marca']) && isset($_POST['calibre']) && isset($_POST['modelo'])) { 
            
            $condicion = "";
            $and = 0;
            
            $_SESSION['nro_serie'] = $_POST['nro_serie'];
            $_SESSION['marca']     = $_POST['marca'];
            $_SESSION['calibre']   = $_POST['calibre'];
            $_SESSION['modelo']    = $_POST['modelo'];
 
            if(!empty($_POST['nro_serie'])) {
                $condicion .= " nro_serie = ".$this->db->escape($_POST['nro_serie']);
                $and = 1; //agrego AND en proximo filtro
            } 
            
            if(!empty($_POST['marca'])) {
                $condicion .= " AND ";
                $condicion .= " marca = ".$this->db->escape($_POST['marca']);
                $and = 1; //agrego AND en proximo filtro
            } 
            
            if(!empty($_POST['calibre'])) {
                $condicion .= " AND ";
                $condicion .= " calibre = ".$this->db->escape($_POST['calibre']);
                $and = 1; //agrego AND en proximo filtro
            }
            
            if(!empty($_POST['modelo'])) {
                $condicion .= " AND ";
                $condicion .= " modelo = ".$this->db->escape($_POST['modelo']);
                $and = 1; //agrego AND en proximo filtro
            }             
            
            if(!empty($_POST['nro_serie']) && (isset($_POST['fecha1']) && isset($_POST['fecha2']))) {
                if((!empty($_POST['fecha1']) && !empty($_POST['fecha2'])) && ($_POST['fecha1'] <= $_POST['fecha2'])) {
                    $condicion .= " AND ";
                    $condicion .= "fecha_transaccion BETWEEN ".$_POST['fecha1']." AND ".$_POST['fecha2'];
                }
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

            $result = $this->consulta_historial_movimiento_ficha_model->consulta_db_actas_alta($param, $cantReg, $condicion, $order);

            $j=0;

            $total = $this->consulta_historial_movimiento_ficha_model->cantidadRegistros_alta($condicion);
            
            $concat .= '
                
                <p class="subtituloform"> ACTAS ALTA </p>

                <p class="subtituloform"> Nº serie - '.$_SESSION['nro_serie']. ' &nbsp;&nbsp; Marca - '.$_SESSION['modelo'].' &nbsp;&nbsp; Calibre - '.$_SESSION['calibre'].' &nbsp;&nbsp; Modelo - '.$_SESSION['modelo'].' &nbsp;&nbsp;&nbsp;&nbsp; Total - '.$total.'</p>

                <table>

                    <thead style="text-align: center; cursor: pointer;">
                        <tr>      
                            <th> Nº acta          </th>
                            <th> Unidad entrega   </th>
                            <th> Unidad recibe    </th>
                            <th> Fecha            </th>
                        </tr>
                    </thead>

            ';   
            
            $concat .= '<tbody>';
            
            for($i=0;$i<count($result);$i=$i+4) {

                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                }                        

                /* 
                 * lo que contiene el array adentro 
                $result[] = $row->nro_acta;
                $result[] = $row->unidad_entrega;
                $result[] = $row->unidad_recibe;
                $result[] = $row->fecha_transaccion;      
                */
            
                $concat .= "
                    <tr class='".$class."'> 
                        <td style='text-align: center;'> ".$result[$i]."   </td>
                        <td> ".$this->consulta_historial_movimiento_ficha_model->nombreUnidad($result[$i+1])." </td>
                        <td> ".$this->consulta_historial_movimiento_ficha_model->nombreUnidad($result[$i+2])." </td>
                        <td style='text-align: center;'> ".$result[$i+3]." </td>
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
            
            $concat .= '<hr />';
            
            
            //-----------------------------//
            //ACTAS BAJA
          
            $result = $this->consulta_historial_movimiento_ficha_model->consulta_db_actas_baja($param, $cantReg, $condicion, $order);

            $j=0;

            $total = $this->consulta_historial_movimiento_ficha_model->cantidadRegistros_baja($condicion);
            
            $concat .= '
                
                <p class="subtituloform"> ACTAS BAJA </p>

                <p class="subtituloform"> Nº serie - '.$_SESSION['nro_serie']. ' &nbsp;&nbsp; Marca - '.$_SESSION['modelo'].' &nbsp;&nbsp; Calibre - '.$_SESSION['calibre'].' &nbsp;&nbsp; Modelo - '.$_SESSION['modelo'].' &nbsp;&nbsp;&nbsp;&nbsp; Total - '.$total.'</p>

                <table>

                    <thead style="text-align: center; cursor: pointer;">
                        <tr>      
                            <th> Nº acta          </th>
                            <th> Unidad entrega   </th>
                            <th> Unidad recibe    </th>
                            <th> Fecha            </th>
                        </tr>
                    </thead>

            ';   
            
            $concat .= '<tbody>';
            
            for($i=0;$i<count($result);$i=$i+4) {

                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                }                        

                /* 
                 * lo que contiene el array adentro 
                $result[] = $row->nro_acta;
                $result[] = $row->unidad_entrega;
                $result[] = $row->unidad_recibe;
                $result[] = $row->fecha_transaccion;      
                */
            
                $concat .= "
                    <tr class='".$class."'> 
                        <td style='text-align: center;'> ".$result[$i]."   </td>
                        <td> ".$this->consulta_historial_movimiento_ficha_model->nombreUnidad($result[$i+1])." </td>
                        <td> ".$this->consulta_historial_movimiento_ficha_model->nombreUnidad($result[$i+2])." </td>
                        <td style='text-align: center;'> ".$result[$i+3]." </td>
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
            
            $config['base_url'] = site_url("consulta_historial_movimiento_ficha/consulta");
            $config['total_rows'] = $this->consulta_historial_movimiento_ficha_model->cantidadRegistros_alta($condicion);
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
}

?>
