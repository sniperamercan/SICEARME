<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - accion_piezas_asociadas
*/

class accion_piezas_asociadas extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accion_piezas_asociadas_model');
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
        
        $data['nro_orden'] = $_SESSION['nro_orden'];
        
        $nro_orden  = $_SESSION['nro_orden'];
        $nro_accion = $_SESSION['nro_accion'];
        
        $data['acciones'] = "";
        
        if($this->accion_piezas_asociadas_model->hayDatosAccion($nro_orden, $nro_accion)) {
            $datos_accion = $this->accion_piezas_asociadas_model->cargoDatosAccion($nro_orden, $nro_accion);
            
            /*
                $datos[] = $row->nro_cambio;         0
                $datos[] = $row->nro_pieza_anterior; 1
                $datos[] = $row->nro_pieza_nueva;    2
             */
            
            for($i=0; $i<count($datos_accion); $i=$i+4) {
                
                $nro_cambio_aux = '"'.$datos_accion[$i].'"';
                
                $data['acciones'] .= "<tr>
                                        <td style='text-align: center;'>".$datos_accion[$i]."</td>
                                        <td>".$datos_accion[$i+1]."</td>
                                        <td>".$datos_accion[$i+2]."</td>
                                        <td style='text-align: center;'>".$datos_accion[$i+3]."</td>
                                        <td style='text-align: center; cursor: pointer;'><img onclick='eliminarAccionSimple(".$nro_cambio_aux.");' src='".base_url()."images/delete.gif' /></td>
                                     </tr>";
            }
            
        }
        
        //Llamo a la vista
        $this->load->view('accion_piezas_secundarias_view', $data);  
    }
    
    function cargoRepuestosFiltro() {
        
        $datos = array();
        
        if( !empty($_SESSION['seleccion_busqueda']) && !empty($_SESSION['seleccion_busqueda1']) ) {
            $nro_parte    = $_SESSION['seleccion_busqueda'];
            $nombre_parte = $_SESSION['seleccion_busqueda1'];            
            $datos[] = $nro_parte;
            $datos[] = $nombre_parte;

            $cantidad = $this->accion_piezas_secundarias_model->cargoCantidad($nro_parte, $nombre_parte);

            $datos[] = $cantidad;            
        }else {
            $datos[] = 0;
        }
        
        echo json_encode($datos);
    }
    
    function eliminarAccionSimple() {
        
        $nro_cambio = $_POST['nro_cambio'];
        
        //obtengo los valores de ese cambio 
        $datos_accion = $this->accion_piezas_secundarias_model->cargoDatosAccionEliminar($nro_cambio);
        
        /*
            $datos[] = $row->nro_parte;    0
            $datos[] = $row->nombre_parte; 1 
            $datos[] = $row->cantidad;     2
         */
        
        $nro_parte    = $datos_accion[0];
        $nombre_parte = $datos_accion[1];
        $cantidad     = $datos_accion[2];
        
        //obtengo la cantidad actual para ese stock 
        $cant_actual = $this->accion_piezas_secundarias_model->cargoCantidadActual($nro_parte, $nombre_parte);
        
        $cant_actualizar = $cant_actual + $cantidad;
        
        $this->accion_piezas_secundarias_model->eliminarAccionSecundaria($nro_cambio, $nro_parte, $nombre_parte, $cant_actualizar);
    }
    
    function validarDatos() {
        
        $nro_parte    = $_POST["nro_parte"];
        $nombre_parte = $_POST["nombre_parte"];
        $cant_actual  = $_POST["cant_actual"];
        $cant_usar    = $_POST["cant_usar"];

        $mensaje_error = array();
        
        if(empty($nro_parte)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($nombre_parte)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($cant_actual)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($cant_usar)) {
            $mensaje_error[] = 4;
        }        
        
        if(!$this->form_validation->numeric($cant_usar)) {
            $mensaje_error[] = 5;
        }  
        
        if($cant_usar > $cant_actual) {
            $mensaje_error[] = 6;
        }          
        
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('nro parte');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nombre parte');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('cant actual');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('cant a usar');
                    break;
                
                case 5:
                    echo $this->mensajes->errorNumerico('cant a usar');
                    break;
                
                case 6:
                    echo "ERROR: La cantidad a usar no puede ser mayor que la cantidad que hay en stock";
                    break;                
            }
        }else {
            $cant_total = $cant_actual - $cant_usar;
            $this->accion_piezas_secundarias_model->altaAccionPiezasSecundarias($nro_parte, $nombre_parte, $cant_usar, $cant_total);
            echo 1;
        }
    }
}

?>
