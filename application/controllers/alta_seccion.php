<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - alta_seccion
*/

class alta_seccion extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_seccion_model');
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
        $_SESSION['alta_seccion'] = '';
        $this->load->view('alta_seccion_view');  
    }
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $seccion = preg_replace($patterns, '', $_POST["seccion"]);
        
        $mensjError = array();
        
        if(empty($seccion)) {
            $mensjError[] = 1;  
        }
        
        if($this->alta_seccion_model->existeSeccion($seccion)) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('seccion');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('seccion');
                    break;
            }
        }else {
            $this->alta_seccion_model->altaSeccion($seccion);
            $_SESSION['alta_seccion'] = $seccion;
            echo 1;
        }
     }
}

?>