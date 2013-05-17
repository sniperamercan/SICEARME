<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - alta_deposito
*/

class alta_deposito extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_deposito_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 8 - Usuario Reserva
        if(!$this->perms->verificoPerfil8()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        $_SESSION['alta_deposito'] = '';
        $this->load->view('alta_deposito_view');  
    }
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $deposito = preg_replace($patterns, '', $_POST["deposito"]);
        
        $mensjError = array();
        
        if(empty($deposito)) {
            $mensjError[] = 1;  
        }
        
        if($this->alta_deposito_model->existeDeposito($deposito)) {
            $mensjError[] = 2;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('deposito');
                    break;
                
                case 2:
                    echo $this->mensajes->errorExiste('deposito');
                    break;
            }
        }else {
            $this->alta_deposito_model->altaDeposito($deposito);
            $_SESSION['alta_deposito'] = $deposito;
            echo 1;
        }
     }
}

?>