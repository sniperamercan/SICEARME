<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - modificar_repuestos_nro_pieza
*/

class modificar_repuestos_nro_pieza extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_repuestos_nro_pieza_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 10 - Administradores Almacen Taller de armamento
        if(!$this->perms->verificoPerfil10()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $data['nro_pieza']    = $_SESSION['nro_pieza'];
        $data['nro_parte']    = $_SESSION['nro_parte'];
        $data['nombre_parte'] = $_SESSION['nombre_parte'];
        $data['nro_catalogo'] = $_SESSION['nro_catalogo'];
        
        //Llamo a la vista
        $this->load->view('modificar_repuestos_nro_pieza_view', $data);  
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

            $cantidad = $this->modificar_repuestos_nro_pieza_model->cargoCantidad($nro_parte, $nombre_parte, $nro_catalogo);

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
        
        echo $this->modificar_repuestos_nro_pieza_model->cargoCantidad($nro_parte, $nombre_parte, $nro_catalogo);
    }
    
    function validarDatos() {
        
        $nro_pieza    = $_POST["nro_pieza"];
        $nro_parte    = $_POST["nro_parte"];
        $nombre_parte = $_POST["nombre_parte"];
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
        
        if(!$this->form_validation->numeric($nro_pieza)) {
            $mensaje_error[] = 4;
        }        
        
        if(empty($nro_catalogo)) {
            $mensaje_error[] = 5;
        }        
        
        if($this->modificar_repuestos_nro_pieza_model->existePieza($nro_pieza, $nro_parte, $nombre_parte, $nro_catalogo)) {
            $mensaje_error[] = 6;
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
                    echo $this->mensajes->errorNumerico('nro pieza');
                    break;
                
                case 5:
                    echo $this->mensajes->errorVacio('nro catalogo');
                    break; 
                
                case 6:
                    echo $this->mensajes->errorExiste('nro pieza');
                    break;                
            }
        }else {
            $nro_pieza_anterior = $_SESSION['nro_pieza'];
            $this->modificar_repuestos_nro_pieza_model->modificarRepuestoNroPieza($nro_pieza, $nro_parte, $nombre_parte, $nro_catalogo, $nro_pieza_anterior);
            echo 1;
        }
    }
}

?>
