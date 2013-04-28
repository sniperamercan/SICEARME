<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - alta_repuestos_nro_pieza
*/

class alta_repuestos_nro_pieza extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_repuestos_nro_pieza_model');
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
        //Llamo a la vista
        $this->load->view('alta_repuestos_nro_pieza_view');  
    }
    
    function cargoRepuestosFiltro() {
        
        $datos = array();
        
        if( !empty($_SESSION['seleccion_busqueda']) && !empty($_SESSION['seleccion_busqueda1']) && !empty($_SESSION['seleccion_busqueda2'])  ) {
            $nro_parte    = $_SESSION['seleccion_busqueda'];
            $nombre_parte = $_SESSION['seleccion_busqueda1'];    
            $nro_catalogo = $_SESSION['seleccion_busqueda2'];  
            $datos[] = $nro_parte;
            $datos[] = $nombre_parte;
            $datos[] = $nro_catalogo;

            $cantidad = $this->alta_repuestos_nro_pieza_model->cargoCantidad($nro_parte, $nombre_parte, $nro_catalogo);

            $datos[] = $cantidad;            
        }else {
            $datos[] = 0;
        }
        
        echo json_encode($datos);
    }
    
    function cargoCantidad() {
        
        $nro_parte    = $_POST["nro_parte"];
        $nombre_parte = $_POST["nombre_parte"];
        $nro_catalogo = $_POST["nro_catalogo"];    
        
        echo $this->alta_repuestos_nro_pieza_model->cargoCantidad($nro_parte, $nombre_parte, $nro_catalogo);
    }
    
    function validarDatos() {
        
        $nro_pieza    = $_POST["nro_pieza"];
        $nro_parte    = $_POST["nro_parte"];
        $nombre_parte = $_POST["nombre_parte"];
        $cant_actual  = $_POST["cant_actual"];
        $nro_catalogo = $_POST["nro_catalogo"];
        
        $mensaje_error = array();
        
        if(empty($nro_pieza)) {
            $mensaje_error[] = 1;
        }        
        
        if(empty($nro_parte)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($nombre_parte)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($cant_actual)) {
            $mensaje_error[] = 4;
        }
        
        if(!$this->form_validation->numeric($nro_pieza)) {
            $mensaje_error[] = 5;
        }  
        
        if($cant_actual < 1) {
            $mensaje_error[] = 6;
        }        
        
        if(empty($nro_catalogo)) {
            $mensaje_error[] = 7;
        }        
        
        if($this->alta_repuestos_nro_pieza_model->existePieza($nro_pieza, $nro_parte, $nombre_parte, $nro_catalogo)) {
            $mensaje_error[] = 8;
        }
        
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('nro pieza');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nro parte');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('nombre parte');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('cant actual');
                    break;
                
                case 5:
                    echo $this->mensajes->errorNumerico('nro pieza');
                    break;
                
                case 6:
                    echo "ERROR: La cantidad no hay stock suficiente para numerar de dicho repuesto";
                    break;   
                
                case 7:
                    echo $this->mensajes->errorVacio('nro catalogo');
                    break; 
                
                case 8:
                    echo $this->mensajes->errorExiste('nro pieza');
                    break;                
            }
        }else {
            $this->alta_repuestos_nro_pieza_model->altaRepuestoNroPieza($nro_pieza, $nro_parte, $nombre_parte, $cant_actual, $nro_catalogo);
            echo 1;
        }
    }
}

?>
