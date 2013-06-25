<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - modificar_estado_orden_trabajo
*/

class modificar_estado_orden_trabajo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_estado_orden_trabajo_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 6 y 7 - Usuarios taller de armamento y Administradores taller de armamento 
        if(!$this->perms->verificoPerfil6() && !$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $data['nro_orden'] = $_SESSION['nro_orden'];
                
        $this->load->view('modificar_estado_orden_trabajo_view', $data);  
    }
    
    function validarDatos() {
        $estado_armamento = $_POST['estado_armamento'];
        $nro_orden        = $_SESSION['nro_orden'];
        
        switch($estado_armamento) {
            
            case 0:
                $tipo_estado = "reparado";
                break;
            
            case 1:
                $tipo_estado = "reparado con desperfectos";
                break;
            
            case 2:
                $tipo_estado = "sin reparacion";
                break;
        }
        
        $this->modificar_estado_orden_trabajo_model->cambiarEstadoOrdenTrabajo($nro_orden, $tipo_estado);
        echo 1;
    }
       
}

?>