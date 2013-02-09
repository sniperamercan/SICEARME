<?php

class desarrollo_incremento_version_sistema extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('desarrollo_incremento_version_sistema_model');
        $this->load->library('mensajes');
        $this->load->library('perms');  
        $this->load->library('version');
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }    
        
        //Modulo solo visible para el peril 1 - Administradores del sistema
        if(!$this->perms->verificoPerfil1()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {
        
        $data['version'] = $this->version->getVer();
        $data['version_nueva'] = $data['version'] + 0.001;
        
        $this->load->view('desarrollo_incremento_version_sistema_view', $data);
    }
    
    function validarDatos() {
        $version_actual = $this->version->getVer();
        $version_nueva  = $this->version->getVer() + 0.001;
        
        $this->desarrollo_incremento_version_sistema_model->ingresoDatos($version_actual, $version_nueva);
        
        echo $version_nueva;
    }    
    
}

?>
