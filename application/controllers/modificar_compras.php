<?php

class modificar_compras extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_compras_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 3 - Administradores O.C.I 
        if(!$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $array_paises = $this->modificar_compras_model->cargoPaises();
        
        $data['paises'] = "<option> </option>";
        
        foreach($array_paises as $val) {
            $data['paises'] .= "<option val='".$val."'>".$val."</option>";
        }
        
        $this->load->view('modificar_compras_view', $data);  
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
        
        if($this->alta_usuarios_model->existeUsuario($usuario)) {
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
            $this->alta_usuarios_model->agregarUsuario($usuario, $nombre, $apellido, $clave, $permisos);
            echo 1;
        }
        
    }
    
}

?>
