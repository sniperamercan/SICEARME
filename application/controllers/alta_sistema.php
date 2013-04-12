<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - alta_sistema
*/

class alta_sistema extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_sistema_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 2 y 3 - Usuarios O.C.I y Administradores O.C.I 
        if(!$this->perms->verificoPerfil2() && !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        $_SESSION['alta_sistema'] = '';
        $this->load->view('alta_sistema_view');  
    }
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $sistema = preg_replace($patterns, '', $_POST["sistema"]);
        
        $mensaje_error = array();
        
        if(empty($sistema)) {
            $mensaje_error[] = 1;
        }
        
        if($this->alta_sistema_model->existeSistema($sistema)) {
            $mensaje_error[] = 2;
        }
        
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('sistema');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('sistema');
                    break;
            }
        }else {
            $this->alta_sistema_model->altaSistema($sistema);
            $_SESSION['alta_sistema'] = $sistema;
            echo 1;
        }
     }
}

?>