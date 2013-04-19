<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
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
        if(!$this->perms->verificoPerfil2() && !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        $this->load->view('alta_stock_de_almacen_view'); 
    }
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $nro_parte    = preg_replace($patterns, '', $_POST["nro_parte"]);
        $nombre_parte = preg_replace($patterns, '', $_POST["nombre_parte"]);
        $precio       = $_POST["precio"];
        $cantidad     = $_POST["cantidad"];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($nro_parte)) {
            $mensjError[] = 1;
        }
        
        if(empty($nombre_parte)) {
            $mensjError[] = 2;
        }
        
        if(empty($precio)) {
            $mensjError[] = 3;
        }
        
        if(empty($cantidad)) {
            $mensjError[] = 4;
        }      
        
        if(!$this->form_validation->numeric($precio)) {
            $mensjError[] = 5;
        }
        
        if(!$this->form_validation->numeric($cantidad)) {
            $mensjError[] = 6;
        }        
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
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
                
            }
        }else {
            if($this->alta_stock_de_almacen_modelo->existeParte($nro_parte, $nombre_parte)) {
                $this->alta_stock_de_almacen_model->actualizoStock($nro_parte, $nombre_parte, $precio, $cantidad);
            }else {
                $this->alta_stock_de_almacen_model->altaStock($nro_parte, $nombre_parte, $precio, $cantidad);
            }
            
            echo 1;
        }
    }
}

?>