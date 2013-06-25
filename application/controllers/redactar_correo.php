<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - redactar_correo
*/

class redactar_correo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('redactar_correo_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation');   
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }         
    }
    
    function index() {
        
        if(!isset($_SESSION['id_correo'])) {
            
            $data['asunto'] = "";
            
            //Cargo usuario
            $usuarios = array();
            $usuarios = $this->redactar_correo_model->cargoUsuarios();

            $data['usuarios'] = "<option value=''> </option>";

            foreach($usuarios as $val) {
                $data['usuarios'] .= "<option value='".$val."'>".$val."</option>";
            }
            //Fin 
        }else{
            $datos_correo = array();
            $datos_correo = $this->redactar_correo_model->obtengoUsuarioEnvia($_SESSION['id_correo']);
            $data['usuarios'] = "<option value='".$datos_correo['usuario_envia']."'>".$datos_correo['usuario_envia']."</option>";
            $data['asunto']   = "RE: ".$datos_correo['asunto'];
        }
            
        $this->load->view('redactar_correo_view', $data);
    }
    
    function validarDatos() {
        
        $destinatario = $_POST['destinatario'];
        $asunto       = $_POST['asunto'];
        $contenido    = $_POST['contenido'];
        
        $mensjArray = array();
        
        if(empty($destinatario)) {
            $mensjArray[] = 1;
        }
        
        if(empty($asunto)) {
            $mensjArray[] = 2;
        }
        
        if($contenido == " ") {
            $mensjArray[] = 3;
        }
        
        if(count($mensjArray) > 0) {
            
            switch($mensjArray[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('destinatario');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('asunto');
                    break;
                
                case 3: 
                    echo $this->mensajes->errorVacio('contenido');
                    break;
            }
        }else {
            $this->redactar_correo_model->ingresarDato($destinatario, $asunto, $contenido);
            echo "1";
        }
    }
    
}

?>
