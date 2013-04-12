<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - PanelPrincipal
*/

Class PanelPrincipal extends CI_Controller{
	
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('panelprincipal_model');
        $this->load->library('perms');
        $this->load->library('mensajes');
        $this->load->library('version');
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->errorUsuario());
        }         
    }

    function index(){
        
        $data['info']    = $this->version->getInfo();
        $data['version'] = $this->version->getVersion();
        
        if( empty($_SESSION['irAFrame']) ) {
            $data['irAFrame'] = "irAFrame('".base_url('resumen')."','Inicio >> Resumen');";
        }else if($_SESSION['irAFrame'] == 'upload') {
            $data['irAFrame'] = "irAFrame('".base_url('upload')."','Empresas >> Cargo documentos / imagenes');";
        }
        
        $_SESSION['irAFrame'] = '';
        
        $this->load->view('panelprincipal_view', $data);
    }

    function informacionUsuario(){
        
        $reg = array();
        $reg = $this->panelprincipal_model->informacionUsuario_db();
        $info = "<b><u> INFORMACION SOBRE USTED </u></b> <br /><br /><br /> <b> Nombre - </b> ".$reg['nombre']." ".$reg['apellido']." <br /><br /> ";
        
        echo $info;
    }	

    function destruyoSession(){
        
        $this->panelprincipal_model->destruyoSession_db();
        //destruyo todas las variabes de session generadas por el usuario
        //$this->session->sess_destroy();
        session_unset();
        session_destroy();
    }
    
    function verificarCorreo() {
        
        $correo = $this->panelprincipal_model->verificarCorreo_db();
        
        if($correo > 0) {
            echo 1;
        }else{
            echo 0;
        }
    }
	
}

?>