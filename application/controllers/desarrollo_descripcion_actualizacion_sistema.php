<?php

class desarrollo_descripcion_actualizacion_sistema extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('desarrollo_descripcion_actualizacion_sistema_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation');  
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }     
        
        //Modulo solo visible para el peril 1 - Administradores del sistema
        if(!$this->perms->verificoPerfil1()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {
        
        //$data['heading'] = $this->load->view("jss-css");
        $data['version'] = $this->desarrollo_descripcion_actualizacion_sistema_model->getVersion();
        $this->load->view('desarrollo_descripcion_actualizacion_sistema_view', $data);        
    }

    function validarDatos() {

        $version        = $_POST['version'];
        $fecha          = $_POST['fecha'];
        $descripcion    = $_POST['descripcion'];
        $critica        = $_POST['critica'];
        
        $mensajeError = array();
        
        if(empty($version)) {
            $mensajeError[] = 1;
        }

        if(empty($fecha)) {
            $mensajeError[] = 2;
        }        
        
        if($descripcion == " ") {
            $mensajeError[] = 3;
        }
        
        if(count($mensajeError) > 0) {
            
            switch($mensajeError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('version');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('fecha');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('descripcion');
                    break;
            }
        }else {
            $this->desarrollo_descripcion_actualizacion_sistema_model->ingresarDatos($version, $fecha, $descripcion, $critica);
            echo 1;
        }
    }
}

?>
