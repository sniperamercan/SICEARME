<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - modificar_accion_simple
*/

class modificar_accion_simple extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_accion_simple_model');
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
        
        $nro_accion = $_SESSION['editar_nro_accion'];
        
        //cargo informacion de la accion 
        $datos = $this->modificar_accion_simple_model->cargoInformacion($nro_accion);
        
        /*
            $datos[] = $row->nro_orden;
            $datos[] = $row->fecha;
            $datos[] = $row->seccion;
            $datos[] = $row->detalles;
            $datos[] = $row->nro_serie;
            $datos[] = $row->marca;
            $datos[] = $row->calibre;
            $datos[] = $row->modelo;
            $datos[] = $row->tipo_arma; 
        */       
        
        $data['nro_orden']  = $datos[0];
        $data['fecha']      = $datos[1];
        $seccion            = $datos[2];
        $data['detalles']   = $datos[3];
        $data['nro_serie']  = $datos[4];
        $data['marca']      = $datos[5];
        $data['calibre']    = $datos[6];
        $data['modelo']     = $datos[7];
        $data['tipo_arma']  = $datos[8];
        
        //cargo las secciones ingresadas hasta el momento
        $secciones = $this->modificar_accion_simple_model->cargoSecciones();
        
        $data['secciones'] = "";
        
        foreach($secciones as $val) {
            if($seccion == $val) {
                $data['secciones'] .= "<option selected='selected' value='".$val."'>".$val."</option>";
            }else{
                $data['secciones'] .= "<option value='".$val."'>".$val."</option>";
            }
        }
        //fin cargo las secciones ingresadas hasta el momento
        
        //Llamo a la vista
        $this->load->view('modificar_accion_simple_view', $data);  
    }
    
    function volver() {
        if($_SESSION['volver'] != "") {
            echo $_SESSION['volver'];
        }else {
            echo 0;
        }
    }
    
    function validarDatos() {
        
        $patterns = array();
        
        $patterns[] = '/"/';
        $patterns[] = "/'/";        

        $patterns[] = '/{/';
        
        $patterns[] = '/}/';
        
        $patterns[] = '/|/';        
        
        $fecha          = $_POST["fecha"];
        $nro_orden      = $_POST["nro_orden"];
        $seccion        = $_POST["seccion"];
        
        $observaciones = preg_replace($patterns, '', $_POST["observaciones"]);
        
        $mensaje_error = array();
        
        if(empty($fecha)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($nro_orden)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($seccion)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($observaciones)) {
            $mensaje_error[] = 4;
        }        
         
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nro orden');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('seccion');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('observaciones');
                    break;
            }
        }else {
            $nro_accion = $_SESSION['editar_nro_accion'];
            $this->modificar_accion_simple_model->modificarAccionSimple($nro_accion, $fecha, $seccion, $observaciones);
            echo 1;
        }
    }
}

?>
