<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - modificar_accion_piezas_asociadas
*/

class modificar_accion_piezas_asociadas extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_accion_piezas_asociadas_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 6 y 7 - Usuarios taller de armamento y Administradores taller de armamento 
        if(!$this->perms->verificoPerfil6() && !$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $nro_accion = $_SESSION['editar_nro_accion'];
        $_SESSION['nro_accion'] = $nro_accion;
        
        $_SESSION['tipo_pieza'] = "";
        
        $nro_orden = $this->modificar_accion_piezas_asociadas_model->cargoNroOrden($nro_accion);
        
        $_SESSION['nro_orden'] = $nro_orden;
        
        $data['nro_orden'] = $nro_orden;
        
        $data['acciones'] = "";
        
        $data['nro_pieza_actual'] = "";
        
        if($this->modificar_accion_piezas_asociadas_model->hayDatosFicha($nro_orden)) {
            $datos = $this->modificar_accion_piezas_asociadas_model->obtenerDatosFicha($nro_orden);
            foreach($datos as $val) {
                $data['nro_pieza_actual'] .= "Nº pieza - ".$val."/ "; 
            }
            $data['nro_pieza_actual'] = substr($data['nro_pieza_actual'], 0, -1);
        }else {
            $data['nro_pieza_actual'] = "ARMAMENTO SIN PIEZAS ASOCIADAS";
        }
        
        if($this->modificar_accion_piezas_asociadas_model->hayDatosAccion($nro_orden, $nro_accion)) {
            $datos_accion = $this->modificar_accion_piezas_asociadas_model->cargoDatosAccion($nro_orden, $nro_accion);
            
            /*
                $datos[] = $row->nro_cambio;         0
                $datos[] = $row->nro_pieza_nueva;    1
                $datos[] = $row->nro_pieza_anterior; 2
             */
            
            for($i=0; $i<count($datos_accion); $i=$i+3) {
                
                $nro_cambio_aux = '"'.$datos_accion[$i].'"';
                
                $data['acciones'] .= "<tr>
                                        <td style='text-align: center;'>".$datos_accion[$i]."</td>
                                        <td style='text-align: center;'>".$datos_accion[$i+1]."</td>
                                        <td style='text-align: center;'>".$datos_accion[$i+2]."</td>
                                        <td style='text-align: center; cursor: pointer;'><img onclick='eliminarAccionAsociada(".$nro_cambio_aux.");' src='".base_url()."images/delete.gif' /></td>
                                     </tr>";
            }
            
        }
        
        //Llamo a la vista
        $this->load->view('modificar_accion_piezas_asociadas_view', $data);  
    }
    
    function cargoRepuestosFiltro() {
        
        $datos = array();
        
        if( !empty($_SESSION['seleccion_busqueda']) ) {
            $datos[0] = $_SESSION['seleccion_busqueda'];
            $datos[1] = $_SESSION['seleccion_busqueda1'];
            $datos[2] = $_SESSION['seleccion_busqueda2'];
            $datos[3] = $_SESSION['seleccion_busqueda3'];
        }else {
            $datos[0] = 0;
        }
        
        echo json_encode($datos);
    }
    
    function cargoPiezasArmamentoFiltro() {

        if( !empty($_SESSION['seleccion_busqueda']) ) {
            $nro_pieza = $_SESSION['seleccion_busqueda'];
        }else {
            $nro_pieza = 0;
        }
        
        echo $nro_pieza;
    }    
    
    function busquedaRepuestos() {
        
        $nro_orden = $_SESSION['nro_orden'];
        $nro_pieza = $_POST['nro_pieza'];
        
        $tipo_pieza = $this->modificar_accion_piezas_asociadas_model->cargoTipoPieza($nro_orden, $nro_pieza);
        
        $_SESSION['tipo_pieza'] = $tipo_pieza;
    }    
    
    function eliminarAccionAsociada() {
        
        $nro_cambio = $_POST['nro_cambio'];
        
        //obtengo informacion del cambio
        $datos = $this->modificar_accion_piezas_asociadas_model->obtenerDatos($nro_cambio);
        
        /*
            $datos[] = $row->nro_orden; 0
            $datos[] = $row->nro_accion; 1
            $datos[] = $row->nro_pieza_anterior; 2
            $datos[] = $row->nro_pieza_nueva; 3
            $datos[] = $row->nro_parte; 4
            $datos[] = $row->nombre_parte; 5  
            $datos[] = $row->nro_serie; 6
            $datos[] = $row->marca; 7
            $datos[] = $row->calibre; 8
            $datos[] = $row->modelo; 9
            $datos[] = $row->nro_interno_catalogo; 10
         */
        
        $nro_orden          = $datos[0];
        $nro_accion         = $datos[1];
        $nro_pieza_anterior = $datos[2];
        $nro_pieza_nueva    = $datos[3];
        $nro_parte          = $datos[4];
        $nombre_parte       = $datos[5];
        $nro_serie          = $datos[6];
        $marca              = $datos[7];
        $calibre            = $datos[8];
        $modelo             = $datos[9];
        $nro_catalogo       = $datos[10];
        
        if($this->modificar_accion_piezas_asociadas_model->obtenerPiezaFicha($nro_serie, $marca, $calibre, $modelo, $nro_pieza_nueva)) {
            $this->modificar_accion_piezas_asociadas_model->eliminarAccionAsociada($nro_cambio, $nro_orden, $nro_accion, $nro_pieza_anterior, $nro_pieza_nueva, $nro_parte, $nombre_parte, $nro_serie, $marca, $calibre, $modelo, $nro_catalogo);
            echo 1;
        }else {
            echo 0;
        }
        
    }
    
    function volver() {
        if($_SESSION['volver'] != "") {
            echo $_SESSION['volver'];
        }else {
            echo 0;
        }
    }
    
    function validarDatos() {
        
        $nro_pieza_nueva    = $_POST["nro_pieza_nueva"];
        $nro_pieza_anterior = $_POST["nro_pieza_anterior"];
        
        $nro_parte    = $_POST['nro_parte'];
        $nombre_parte = $_POST['nombre_parte'];
        $nro_catalogo = $_POST['nro_catalogo'];

        $mensaje_error = array();
        
        if(empty($nro_pieza_nueva)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($nro_pieza_anterior)) {
            $mensaje_error[] = 2;
        }
        
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('nro pieza nueva');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nro pieza anterior');
                    break;               
            }
        }else {
            $nro_orden  = $_SESSION['nro_orden'];
            $nro_accion = $_SESSION['nro_accion'];
            $this->modificar_accion_piezas_asociadas_model->altaAccionPiezasAsociadas($nro_pieza_nueva, $nro_pieza_anterior, $nro_orden, $nro_accion, $nro_parte, $nombre_parte, $nro_catalogo);
            echo 1;
        }
    }
}

?>
