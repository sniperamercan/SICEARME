<?php

class modificar_usuarios extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_usuarios_model');
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
        
        $permisos = $this->modificar_usuarios_model->cargoPermisos();
        $data['permisos_usuario'] = "";
        
        for($i=0; $i<count($permisos); $i=$i+2) {
            $id=$permisos[$i];
            $data['permisos_usuario'] .= '<dl>
                                         <dt><input type="checkbox" name="persmisos" id="'.$id.'" value="'.$permisos[$i].'" /></dt>
                                         <dd><label for="'.$id.'">'.$permisos[$i+1].'</label></dd>
                                         </dl>';
        }
        
        $this->load->view('modificar_usuarios_view', $data);  
    }
    
    function validarDatos() {
        
        $usuario  = $_POST['usuario'];
        $nombre   = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $clave    = $_POST['clave'];
        
        $permisos = json_decode($_POST['persmisos']);
        
        $mensjError = array();
        
        if(empty($usuario)) {
            $mensjError[] = 1;
        }
        
        if(empty($nombre)) {
            $mensjError[] = 2;
        }
        
        if(empty($apellido)) {
            $mensjError[] = 3;
        }
        
        if(empty($clave)) {
            $mensjError[] = 4;
        }        
        
        if($this->modificar_usuarios_model->existeUsuario($usuario)) {
            $mensjError[] = 5;
        }
        
        if(count($permisos) == 0) {
            $mensjError[] = 6;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('usuario');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nombre');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('apellido');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('clave');
                    break;
                
                case 5:
                    echo $this->mensajes->errorExiste('usuario');
                    break;
                
                case 5:
                    echo $this->mensajes->sinPerfilSeleccionado();
                    break;                
            }
        }else {
            $this->modificar_usuarios_model->agregarUsuario($usuario, $nombre, $apellido, $clave, $permisos);
            echo 1;
        }
    }
}

?>