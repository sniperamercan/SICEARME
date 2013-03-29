<?php

class alta_tipo_pieza extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_tipo_pieza_model');
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
        $_SESSION['alta_tipo_pieza'] = '';
        $this->load->view('alta_tipo_pieza_view');  
    }
    
    function validarDatos() {
        
        $tipo_pieza = $_POST["tipo_pieza"];
        
        $mensjError = array();
        
        if(empty($tipo_pieza)) {
            $mensjError[] = 1;
        }
        
        if($this->alta_tipo_pieza_model->existeTipoPieza($tipo_pieza)) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('tipo pieza');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('tipo pieza');
                    break;
            }
        }else {
            $this->alta_tipo_pieza_model->altaTipoPieza($tipo_pieza);
            $_SESSION['alta_tipo_pieza'] = $tipo_pieza;
            echo 1;
        }
     }
}

?>