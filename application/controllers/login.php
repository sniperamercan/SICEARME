<?php

session_start();

class login extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('login_model');
        $this->load->library('mensajes');
        $this->load->library('version');
    }
    
    function index() {        
        
        session_unset();
        session_destroy();
        
        $data['version'] = $this->version->getVersion();
        $data['info']    = $this->version->getInfo();
        $this->load->view('login_view', $data);        
    }
    
    function validar() {
        
        $usuario = $_POST['usuario'];
        $clave   = $_POST['clave'];
        
        $errorMensj = array();
        
        if(empty($usuario)) {
            $errorMensj[] = 1;
        }
        
        if(empty($clave)) {
            $errorMensj[] = 2;
        }
        
        $clave = md5($clave);
        
        if(!$this->login_model->verificoUsuario_db($usuario, $clave)) {
            $errorMensj[] = 3;
        }else {
           if($this->login_model->verificoEstado($usuario) == 0) {
                $errorMensj[] = 4;
            }
        }  
            
        if(count($errorMensj) > 0) {
            
            switch($errorMensj[0]) {
                
                case 1:
                    echo $this->mensajes->errorUsuario();
                    break;
                    
                case 2:
                    echo $this->mensajes->errorClaveVacia();
                    break;
                    
                case 3:
                    echo $this->mensajes->errorDatosIncorrectos();
                    break;       
                
                case 4:
                    echo $this->mensajes->errorUsuarioInactivo();
                    break;                 
            }            
        }else{
            $_SESSION['usuario']  = base64_encode($usuario);
            $_SESSION['usuario2'] = base64_encode(sha1($clave));
            
            $data = $this->login_model->ingresoLog($usuario);
            
            if($data == 0) {
                echo $this->mensajes->errorSolicitud();
            }else{
                //ingreso correcto al sistema, voy al menu
                echo 1;
            }            
        }        
    }
}


?>
