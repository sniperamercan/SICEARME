<?php

session_start();

class contenido_impresion extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');        
    }
    
    function index(){       
        
        //$data['heading']   = $this->load->view('jss-css');     
        $data['contenido'] = $_SESSION['contenido'];
        
        $this->load->view('contenido_impresion_view', $data);
        
    }
    
}


?>
