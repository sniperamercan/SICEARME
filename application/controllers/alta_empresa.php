<?php

class alta_empresa extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_empresa_model');
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
        $this->load->view('alta_empresa_view');  
    }
    
    function validarDatos() {
        
        $empresa = $_POST["empresa"];
        
        $mensjError = array();
        
        if(empty($empresa)) {
            $mensjError[] = 1;  
        }
        
        if($this->alta_empresa_model->existeEmpresa($empresa)) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('empresa');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('empresa');
                    break;
            }
        }else {
            $this->alta_empresa_model->altaEmpresa($empresa);
            echo 1;
        }
     }
}

?>