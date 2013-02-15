<?php

class cambiar_clave extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('cambiar_clave_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }         
    }
    
    function index() {
        
        $this->load->view('cambiar_clave_view');  
    }
    
    function validarDatos() {
        
        $clave_antigua = $_POST['clave_antigua'];
        $clave_nueva   = $_POST['clave_nueva'];
        $repetir       = $_POST['repetir'];
        
        $mensjError = array();
        
        if(empty($clave_nueva)) {
            $mensjError[] = 1;
        }
        
        if($clave_nueva != $repetir) {
            $mensjError[] = 2;
        }
        
        if(!$this->cambiar_clave_model->verificoClave($clave_antigua)) {
            $mensjError[] = 3;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('clave nueva');
                    break;
                
                case 2:
                    echo 'Las clave nueva y el repetir clave deben coincidir, verifique';
                    break;
                
                case 3:
                    echo 'La clave actual es incorrecta, verifique';
                    break;
            }
        }else {
            $this->cambiar_clave_model->cambiarClave($clave_nueva);
            $_SESSION['usuario2'] = base64_encode(sha1(md5($clave_nueva)));
            echo 1;
        }
    }
}

?>
