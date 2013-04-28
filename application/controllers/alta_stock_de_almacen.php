<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - alta_stock_de_almacen
*/

class alta_stock_de_almacen extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_stock_de_almacen_model');
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
        $this->load->view('alta_stock_de_almacen_view'); 
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
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $patterns[] = '&"&';
        $patterns[] = "&'&";
        
        $nro_parte    = preg_replace($patterns, '', $_POST["nro_parte"]);
        $nombre_parte = preg_replace($patterns, '', $_POST["nombre_parte"]);
        $precio       = $_POST["precio"];
        $cantidad     = $_POST["cantidad"];
        $nro_catalogo = $_POST["nro_catalogo"];
        
        $mensaje_error = array();
        
        if(empty($nro_parte)) {
            $nro_parte = "GENERICO";
        }
        
        if(empty($nombre_parte)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($precio)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($cantidad)) {
            $mensaje_error[] = 4;
        }      
        
        if(!$this->form_validation->numeric($precio)) {
            $mensaje_error[] = 5;
        }
        
        if(!$this->form_validation->numeric($cantidad)) {
            $mensaje_error[] = 6;
        }        
        
        if(empty($nro_catalogo)) {
            $mensaje_error[] = 7;
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
                    echo $this->mensajes->errorVacio('precio');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('cantidad');
                    break;
 
                case 5:
                    echo $this->mensajes->errorNumerico('precio');
                    break;
                
                case 6:
                    echo $this->mensajes->errorNumerico('cantidad');
                    break;   
                
                case 7:
                    echo $this->mensajes->errorVacio('nro catalogo');
                    break;
            }
        }else {
            if($this->alta_stock_de_almacen_model->existeParte($nro_parte, $nombre_parte, $nro_catalogo)) {
                $this->alta_stock_de_almacen_model->actualizoStock($nro_parte, $nombre_parte, $precio, $cantidad, $nro_catalogo);
            }else {
                $this->alta_stock_de_almacen_model->altaStock($nro_parte, $nombre_parte, $precio, $cantidad, $nro_catalogo);
            }
            
            echo 1;
        }
    }
}

?>