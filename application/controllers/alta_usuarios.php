<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - alta_usuarios
*/

class alta_usuarios extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_usuarios_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 1 - Administradores del sistema
        if(!$this->perms->verificoPerfil1()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $permisos = $this->alta_usuarios_model->cargoPermisos();
        $data['permisos_usuario'] = "";
        
        for($i=0; $i<count($permisos); $i=$i+2) {
            $id=$permisos[$i];
            $data['permisos_usuario'] .= '<dl>
                                         <dt><input type="checkbox" name="persmisos" id="'.$id.'" value="'.$permisos[$i].'" /></dt>
                                         <dd><label for="'.$id.'">'.$permisos[$i+1].'</label></dd>
                                         </dl>';
        }
        
        $this->load->view('alta_usuarios_view', $data);  
    }
    
    function validarDatos() {
        
        $usuario  = $_POST['usuario'];
        $nombre   = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $clave    = $_POST['clave'];
        
        $permisos = json_decode($_POST['persmisos']);
        
        $mensaje_error = array();
        
        if(empty($usuario)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($nombre)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($apellido)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($clave)) {
            $mensaje_error[] = 4;
        }        
        
        if($this->alta_usuarios_model->existeUsuario($usuario)) {
            $mensaje_error[] = 5;
        }
        
        if(count($permisos) == 0) {
            $mensaje_error[] = 6;
        }
        
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo json_encode($this->mensajes->errorVacio('usuario'));
                    break;
                
                case 2:
                    echo json_encode($this->mensajes->errorVacio('nombre'));
                    break;
                
                case 3:
                    echo json_encode($this->mensajes->errorVacio('apellido'));
                    break;
                
                case 4:
                    echo json_encode($this->mensajes->errorVacio('clave'));
                    break;
                
                case 5:
                    echo json_encode($this->mensajes->errorExiste('usuario'));
                    break;
                
                case 6:
                    echo json_encode($this->mensajes->sinPerfilSeleccionado());
                    break;                
            }
        }else {
            $this->alta_usuarios_model->agregarUsuario($usuario, $nombre, $apellido, $clave, $permisos);
            echo json_encode(1);
        }
    }
}

?>