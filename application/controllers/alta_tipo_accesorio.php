<?php

class alta_tipo_accesorio extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_tipo_accesorio_model');
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
        $_SESSION['alta_tipo_accesorio'] = '';
        $this->load->view('alta_tipo_accesorio_view');  
    }
    
    function validarDatos() {
        
        $sin_comilla = '"';
        
        $tipo_accesorio = str_replace($sin_comilla, "'", $_POST["tipo_accesorio"]);
        
        $mensjError = array();
        
        if(empty($tipo_accesorio)) {
            $mensjError[] = 1;
        }
        
        if($this->alta_tipo_accesorio_model->existeTipoAccesorio($tipo_accesorio)) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('tipo accesorio');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('tipo accesorio');
                    break;
            }
        }else {
            $this->alta_tipo_accesorio_model->altaTipoAccesorio($tipo_accesorio);
            $_SESSION['alta_tipo_accesorio'] = $tipo_accesorio;
            echo 1;
        }
     }
}

?>