<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - consulta_stock_total_armas_resumido
*/

class consulta_stock_total_armas_resumido extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('consulta_stock_total_armas_resumido_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 2 y 3 - Usuario O.C.I y Administrador O.C.I
        if(!$this->perms->verificoPerfil2() && !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {
        
        //cargo unidades
        $unidades = $this->consulta_stock_total_armas_resumido_model->cargoUnidades();
        
        $data['unidades'] = "<option value=''> Seleccione... </option>";
        
        for($i=0; $i<count($unidades); $i=$i+2) {
            $data['unidades'] .= "<option value='".$unidades[$i]."'>".$unidades[$i+1]."</option>";
        }
        //fin cargo unidades
        
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("consulta_stock_total_armas_resumido_view", $data);
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta($param="",$cantReg=30) {   
     
        $result = array();
        
        //Inicio, armo condiciones where para sql
        if(isset($_POST['unidad'])) { 
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['unidad'])){
                $condicion = "idunidad = ".$this->db->escape($_POST['unidad']);
                $_SESSION['unidad'] = $_POST['unidad'];
                $and = 1; //agrego AND en proximo filtro
            }          
            
            $_SESSION['condicion'] = $condicion;
        }
        
        if(isset($_SESSION['condicion']) && !empty($_SESSION['condicion'])){
            $condicion = $_SESSION['condicion'];     
        }else {
            $condicion = "";
            $_SESSION['unidad'] = "";
        }
        //Fin, armo condiciones where para sql
        
        //Verifico el order si esta seteado si no por defecto de esta consulta
        if(isset($_SESSION['order'])){
            $order = $_SESSION['order'][0]." ".$_SESSION['order'][1];
        }else{
            $order = "nro_serie";
        }
        //Fin verifico order        
        
        if($param == ""){
            $param = 0;
        }

        $concat = "";

        $j=0;

        $unidades = array();
        
        if(!empty($_SESSION['unidad'])){
            $unidades[] = $_SESSION['unidad'];
        }else {
            $unidades = $this->consulta_stock_total_armas_resumido_model->conjutoUnidades();
        }
        
        foreach($unidades as $unidad) {
            
            $condicion = "idunidad = ".$this->db->escape($unidad);
            
            $nombreunidad = $this->consulta_stock_total_armas_resumido_model->nombreUnidad($unidad);
            
            $result = $this->consulta_stock_total_armas_resumido_model->consulta_db($param, $cantReg, $condicion, $order);
        
            $total = $this->consulta_stock_total_armas_resumido_model->cantidadRegistros($condicion);

            $concat .= '
                <p class="subtituloform"> '.$nombreunidad. ' &nbsp;&nbsp;&nbsp;&nbsp; Total registros - '.$total.' </p>

                <table>

                    <thead style="text-align: center; cursor: pointer;">
                        <tr>      
                            <th onclick="orderBy(1)"> Marca      </th>
                            <th onclick="orderBy(2)"> Calibre    </th>
                            <th onclick="orderBy(3)"> Modelo     </th>
                            <th> Tipo     </th>
                            <th> Sistema  </th>
                            <th> Cantidad </th>
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
                $result[] = $row->marca;
                $result[] = $row->calibre;
                $result[] = $row->modelo;
                $result[] = $row->tipo_arma;
                $result[] = $row->sistema;
                $result[] = $row->cantidad;       
                */

                $concat .= "
                    <tr class='".$class."'> 
                        <td> ".$result[$i]."   </td>
                        <td> ".$result[$i+1]." </td>
                        <td> ".$result[$i+2]." </td>
                        <td> ".$result[$i+3]." </td>
                        <td> ".$result[$i+4]." </td>
                        <td style='text-align: center;'> ".$result[$i+5]." </td>
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
        }

        $config['base_url'] = site_url("consulta_stock_total_armas_resumido/consulta");
        $config['total_rows'] = $this->consulta_stock_total_armas_resumido_model->cantidadRegistros($condicion);
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

?>
