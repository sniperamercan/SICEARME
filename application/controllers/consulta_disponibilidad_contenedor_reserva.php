<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - consulta_disponibilidad_contenedor_reserva
*/

class consulta_disponibilidad_contenedor_reserva extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('consulta_disponibilidad_contenedor_reserva_model');
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
        
        //cargo depositos
        $depositos = $this->consulta_disponibilidad_contenedor_reserva_model->cargoDepositos();
        
        $data['depositos'] = "<option value=''> Seleccione... </option>";
        
        for($i=0; $i<count($depositos); $i++) {
            $data['depositos'] .= "<option value='".$depositos[$i]."'>".$depositos[$i]."</option>";
        }
        //fin cargo depositos        
        
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("consulta_disponibilidad_contenedor_reserva_view", $data);
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
        if(isset($_POST['nro_catalogo']) && isset($_POST['deposito'])) { 
            
            $_SESSION['nro_catalogo'] = $_POST['nro_catalogo'];
            $_SESSION['deposito'] = $_POST['deposito'];
            
            $condicion = "";
            $and = 0;
 
            if(!empty($_POST['nro_catalogo']) && !empty($_POST['deposito'])){
                $condicion .= " c.nro_interno = ".$this->db->escape($_POST['nro_catalogo']);
                $condicion .= " AND s.deposito    = ".$this->db->escape($_POST['deposito']);
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
            $order = "nro_serie";
        }
        //Fin verifico order        
        
        if($param == ""){
            $param = 0;
        }
        
        if($correcto) {
            
            $concat = "";

            $result = $this->consulta_disponibilidad_contenedor_reserva_model->consulta_db($param, $cantReg, $condicion, $order);

            $j=0;

            $datos_arma = $this->consulta_disponibilidad_contenedor_reserva_model->datosArma($_SESSION['nro_catalogo']);
            
            /*
            $result[] = $row->tipo_arma;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->sistema;
             */
            
            $total = $this->consulta_disponibilidad_contenedor_reserva_model->cantidadRegistros($condicion);
            
            $concat .= '
                <p class="subtituloform"> Deposito - '.$_SESSION['deposito'].' &nbsp;&nbsp; Tipo arma - '.$datos_arma[0].' &nbsp;&nbsp; Marca - '.$datos_arma[1].' &nbsp;&nbsp; Calibre - '.$datos_arma[2].' &nbsp;&nbsp; Modelo - '.$datos_arma[3].' &nbsp;&nbsp; Sistema - '.$datos_arma[4].' &nbsp;&nbsp;&nbsp;&nbsp; Total - '.$total.' </p>

                <table>

                    <thead style="text-align: center; cursor: pointer;">
                        <tr>      
                            <th> Nº Serie  </th>
                        </tr>
                    </thead>

            ';   
            
            $concat .= '<tbody>';
            
            for($i=0;$i<count($result);$i++) {

                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                }                        

                /* 
                 * lo que contiene el array adentro 
      
                */
            
                $concat .= "
                    <tr class='".$class."'> 
                        <td style='text-align: center;'> ".$result[$i]."   </td>
                    </tr>
                ";

                $j++;
            }                  

            $concat .= '</tbody>';
            
            $concat .= '
                <tfoot>
                    <tr> <td colspan="2"> <div id="paging"> <br /> </div> </td> </tr>
                </tfoot>
            ';      
            
            $concat .= '</table>';
            
            $config['base_url'] = site_url("consulta_disponibilidad_contenedor_reserva/consulta");
            $config['total_rows'] = $this->consulta_disponibilidad_contenedor_reserva_model->cantidadRegistros($condicion);
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
}

?>
