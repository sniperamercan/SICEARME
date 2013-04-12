<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - modificar_clave
*/

class modificar_clave extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_clave_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }         
    }
    
    function index() {
        
        $this->load->view('modificar_clave_view');  
    }
    
    function validarDatos() {
        
        $clave_antigua = $_POST['clave_antigua'];
        $clave_nueva   = $_POST['clave_nueva'];
        $repetir       = $_POST['repetir'];
        
        $mensaje_error = array();
        
        if(empty($clave_nueva)) {
            $mensaje_error[] = 1;
        }
        
        if($clave_nueva != $repetir) {
            $mensaje_error[] = 2;
        }
        
        if(!$this->modificar_clave_model->verificoClave($clave_antigua)) {
            $mensaje_error[] = 3;
        }
        
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
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
            $this->modificar_clave_model->cambiarClave($clave_nueva);
            $_SESSION['usuario2'] = base64_encode(sha1(md5($clave_nueva)));
            echo 1;
        }
        
    }
    
}

?>