<?php

class resumen extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('resumen_model');
        $this->load->library('perms'); 
        $this->load->library('mensajes'); 
        $this->load->library('version');
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        } 
    }
    
    function index() {
        
        $data['descripcion_version'] = $this->version->getDescripcion();
        $data['version']             = $this->version->getVer();
        
        $this->load->view('resumen_view', $data);
    }
    
    function armoGraficas1() {
        $graficas = array();
        $graficas = $this->resumen_model->armoGraficas1_db();
        echo json_encode($graficas);
    }
    
    function armoGraficas2() {
        $graficas = array();
        $graficas = $this->resumen_model->armoGraficas2_db();
        echo json_encode($graficas);
    }
    
    function armoGraficas3() {
        $graficas = array();
        $graficas = $this->resumen_model->armoGraficas3_db();
        echo json_encode($graficas);
    }    
    
}

?>
