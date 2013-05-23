<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - consulta_movimientos_repuestos
*/

class consulta_movimientos_repuestos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('consulta_movimientos_repuestos_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 8 y 9 - Usuario Almacen Taller de armamento y Administrador Almacen Taller de armamento
        if(!$this->perms->verificoPerfil8() && !$this->perms->verificoPerfil9()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("consulta_movimientos_repuestos_view");
    }
    
    function cargoCatalogosFiltro() {
        
        if(isset($_SESSION['seleccion_busqueda'])) {
            $retorno = $_SESSION['seleccion_busqueda'];   
        }else {
            $retorno = '';
        }
        
        echo $retorno;        
    }      
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        $result = array();
        
        //Inicio, armo condiciones where para sql
        if(isset($_POST['nro_catalogo'])) { 
            
            $_SESSION['nro_catalogo'] = $_POST['nro_catalogo'];
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['nro_catalogo'])){
                $condicion .= " nro_interno = ".$this->db->escape($_POST['nro_catalogo']);
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
            $order = "fecha";
        }
        //Fin verifico order        
        
        if($param == ""){
            $param = 0;
        }
        
        if($correcto) {
            
            $concat = "";

            $result  = $this->consulta_movimientos_repuestos_model->consulta_db($param, $cantReg, $condicion, $order);
            $result2 = $this->consulta_movimientos_repuestos_model->consulta_db_asociadas($param, $cantReg, $condicion, $order);
            
            $j=0;

            $total = $this->consulta_movimientos_repuestos_model->cantidadRegistros($condicion) + $this->consulta_movimientos_repuestos_model->cantidadRegistros_asociadas($condicion);
            
            $concat .= '
                <p class="subtituloform"> Nº catálogo - '.$_SESSION['nro_catalogo']. ' &nbsp;&nbsp;&nbsp;&nbsp; Total - '.$total.' </p>

                <table>

                    <thead style="text-align: center; cursor: pointer;">
                        <tr>      
                            <th> Fecha    </th>
                            <th> Sección  </th>
                            <th> Cantidad </th>
                        </tr>
                    </thead>

            ';   
            
            $concat .= '<tbody>';
            
            //repuestos no asociados
            for($i=0;$i<count($result);$i=$i+3) {

                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                }                        

                /* 
                 * lo que contiene el array adentro 
                $result[] = $row->fecha;
                $result[] = $row->seccion;
                $result[] = $row->cantidad;      
                */
            
                $concat .= "
                    <tr class='".$class."'> 
                        <td style='text-align: center;'> ".$result[$i]."   </td>
                        <td> ".$result[$i+1]." </td>
                        <td style='text-align: center;'> ".$result[$i+2]." </td>
                    </tr>
                ";

                $j++;
            }  
            
            //repuestos asociados
            for($i=0;$i<count($result2);$i=$i+3) {

                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                }                        

                /* 
                 * lo que contiene el array adentro 
                $result[] = $row->fecha;
                $result[] = $row->seccion;
                $result[] = $row->cantidad;      
                */
            
                $concat .= "
                    <tr class='".$class."'> 
                        <td style='text-align: center;'> ".$result2[$i]."   </td>
                        <td> ".$result2[$i+1]." </td>
                        <td style='text-align: center;'> ".$result2[$i+2]." </td>
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
            
            $config['base_url'] = site_url("consulta_movimientos_repuestos/consulta");
            $config['total_rows'] = $this->consulta_movimientos_repuestos_model->cantidadRegistros($condicion);
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
