<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - ajustar_stock_de_almacen
*/

class ajustar_stock_de_almacen extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('ajustar_stock_de_almacen_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 7 - Administradores taller de armamento 
        if(!$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $nro_parte    = $_SESSION['nro_parte'];    
        $nombre_parte = $_SESSION['nombre_parte']; 
        $nro_catalogo = $_SESSION['nro_catalogo'];
        
        $cantidad = $this->ajustar_stock_de_almacen_model->cantidadActual($nro_parte, $nombre_parte, $nro_catalogo);
        
        $data['nro_parte']    = $nro_parte;
        $data['nombre_parte'] = $nombre_parte;
        $data['nro_catalogo'] = $nro_catalogo;
        $data['cantidad']     = $cantidad;
        
        $this->load->view('ajustar_stock_de_almacen_view', $data); 
    }
    
    function cargoCatalogosFiltro() {
        
        if(isset($_SESSION['seleccion_busqueda'])) {
            $retorno = $_SESSION['seleccion_busqueda'];   
        }else {
            $retorno = '';
        }
        
        echo $retorno;        
    }    
    
    function validarDatos() {
        
        $ajuste       = $_POST['ajuste'];
        $nro_parte    = $_SESSION['nro_parte'];
        $nombre_parte = $_SESSION['nombre_parte'];
        $nro_catalogo = $_SESSION['nro_catalogo'];
        
        
        $mensaje_error = array();
        
        if(empty($ajuste)) {
            $mensaje_error[] = 1;
        }
        
        if(!$this->form_validation->numeric($ajuste)) {
            $mensaje_error[] = 2;
        }
        
        $cantidad = $this->ajustar_stock_de_almacen_model->cantidadActual($nro_parte, $nombre_parte, $nro_catalogo);        
        
        if($ajuste > $cantidad) {
            $mensaje_error[] = 3;
        }
        
        if($ajuste <= 0) {
            $mensaje_error[] = 4;
        }
        
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('ajuste');
                    break;
                
                case 2:
                    echo $this->mensajes->errorNumerico('ajuste');
                    break;
                
                case 3:
                    echo "ERROR: La cantidad de ajuste no puede ser mayor que la cantidad actual";
                    break;
                
                case 4:
                    echo "ERROR: La cantidad de ajuste debe ser mayor que 0";
                    break;                
            }
        }else {
            $cantidad = $cantidad - $ajuste;
            $this->ajustar_stock_de_almacen_model->ajustarRepuesto($nro_parte, $nombre_parte, $nro_catalogo, $cantidad);
            echo 1;
        }
    }
}

?>