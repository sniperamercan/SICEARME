<?php

class alta_calibre extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_calibre_model');
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
        $_SESSION['alta_calibre'] = '';
        $this->load->view('alta_calibre_view');  
    }
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $calibre = preg_replace($patterns, '', $_POST["calibre"]);
        
        $mensjError = array();
        
        if(empty($calibre)) {
            $mensjError[] = 1;
        }
        
        if($this->alta_calibre_model->existeCalibre($calibre)) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('calibre');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('calibre');
                    break;
            }
        }else {
            $this->alta_calibre_model->altaCalibre($calibre);
            $_SESSION['alta_calibre'] = $calibre;
            echo 1;
        }
     }
}

?>