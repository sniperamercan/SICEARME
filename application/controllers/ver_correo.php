<?php

class ver_correo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('ver_correo_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation');   
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }         
    }
    
    function index() {
        
        if(isset($_POST['id_correo'])) {
            $_SESSION['id_correo'] = $_POST['id_correo'];
        }

        if(isset($_SESSION['id_correo'])) {
        
            //$data['heading'] = $this->load->view('jss-css');

            $correo = array();

            $this->ver_correo_model->mensajeLeido($_SESSION['id_correo']);

            $correo = $this->ver_correo_model->cargoCorreo($_SESSION['id_correo']);

            $data['envia']     = $correo[0];
            $data['asunto']    = $correo[1];
            $data['contenido'] = $correo[2];

            $this->load->view('ver_correo_view', $data);
        }
    }
}

?>
