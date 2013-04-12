<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - alta_marca
*/

class alta_marca extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_marca_model');
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
        $_SESSION['alta_marca'] = '';
        $this->load->view('alta_marca_view');  
    }
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $marca = preg_replace($patterns, '', $_POST["marca"]);
        
        $mensjError = array();
        
        if(empty($marca)) {
            $mensjError[] = 1;
        }
        
        if($this->alta_marca_model->existeMarca($marca)) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('marca');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('marca');
                    break;
            }
        }else {
            $this->alta_marca_model->altaMarca($marca);
            $_SESSION['alta_marca'] = $marca;
            echo 1;
        }
     }
}

?>