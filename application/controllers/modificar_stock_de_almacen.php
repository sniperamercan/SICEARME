<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - modificar_stock_de_almacen
*/

class modificar_stock_de_almacen extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_stock_de_almacen_model');
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
        
        $nro_parte    = $_SESSION['nro_parte'];    
        $nombre_parte = $_SESSION['nombre_parte']; 
        $nro_catalogo = $_SESSION['nro_catalogo'];
        
        $data['nro_parte']    = $nro_parte;
        $data['nombre_parte'] = $nombre_parte;
        $data['nro_catalogo'] = $nro_catalogo;
        
        $this->load->view('modificar_stock_de_almacen_view', $data); 
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

        $patterns[] = '/{/';
        
        $patterns[] = '/}/';
        
        $patterns[] = '/|/';  
        
        $nro_parte    = preg_replace($patterns, '', $_POST["nro_parte"]);
        $nombre_parte = preg_replace($patterns, '', $_POST["nombre_parte"]);
        $nro_catalogo = $_POST["nro_catalogo"];
        
        //datos anteriores
        $nro_parte_ant     = $_SESSION['nro_parte'];
        $nombre_parte_ant  = $_SESSION['nombre_parte'];
        $nro_catalogo_ant  = $_SESSION['nro_catalogo'];
        
        $mensaje_error = array();
        
        if(empty($nro_parte)) {
            $nro_parte = "GENERICO";
        }
        
        if(empty($nombre_parte)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($nro_catalogo)) {
            $mensaje_error[] = 3;
        }
        
        if(($nro_parte == $nro_parte_ant) && ($nombre_parte == $nombre_parte_ant) && ($nro_catalogo == $nro_catalogo_ant)) {
            $mensaje_error[] = 4;
        }
        
        if($this->modificar_stock_de_almacen_model->existeParte($nro_parte, $nombre_parte, $nro_catalogo)) {
            $mensaje_error[] = 5;
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
                    echo $this->mensajes->errorVacio('nro catalogo');
                    break;
                
                case 4:
                    echo "ERROR: No modifico ningun dato del repuesto";
                    break;
                
                case 5:
                    echo $this->mensajes->errorExiste('repuesto');
                    break;
            }
        }else {
            $this->modificar_stock_de_almacen_model->modificarRepuesto($nro_parte, $nombre_parte, $nro_catalogo, $nro_parte_ant, $nombre_parte_ant, $nro_catalogo_ant);
            echo 1;
        }
    }
}

?>