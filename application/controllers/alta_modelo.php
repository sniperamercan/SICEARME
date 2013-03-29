<?php

class alta_modelo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_modelo_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 2 y 3 - Usuarios O.C.I y Administradores O.C.I 
        if(!$this->perms->verificoPerfil2() || !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        $_SESSION['alta_modelo'] = '';
        $this->load->view('alta_modelo_view');  
    }
    
    function validarDatos() {
        
        $modelo = $_POST["modelo"];
        
        $mensjError = array();
        
        if(empty($modelo)) {
            $mensjError[] = 1;
        }
        
        if($this->alta_modelo_model->existeModelo($modelo)) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('modelo');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('modelo');
                    break;
            }
        }else {
            $this->alta_modelo_model->altaModelo($modelo);
            $_SESSION['alta_modelo'] = $modelo;
            echo 1;
        }
     }
}

?>