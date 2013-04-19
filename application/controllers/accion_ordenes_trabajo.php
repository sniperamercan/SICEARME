<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - accion_ordenes_trabajo
*/

class accion_ordenes_trabajo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accion_ordenes_trabajo_model');
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
        
        $_SESSION['nro_orden']  = "";
        $_SESSION['nro_accion'] = "";
        
        //cargo los nro de ordenes ingresados hasta el momento
        $nro_ordenes = $this->accion_ordenes_trabajo_model->cargoNroOrdenes();
        
        $data['nro_ordenes'] = "<option value=''>Seleccione opcion..</option>";
        
        foreach($nro_ordenes as $val) {
            $data['nro_ordenes'] .= "<option value='".$val."'>".$val."</option>";
        }
        //fin cargo nro de ordenes ingresados hasta el momento
        
        //cargo las secciones ingresadas hasta el momento
        $secciones = $this->accion_ordenes_trabajo_model->cargoSecciones();
        
        $data['secciones'] = "<option value=''>Seleccione opcion..</option>";
        
        foreach($secciones as $val) {
            $data['secciones'] .= "<option value='".$val."'>".$val."</option>";
        }
        //fin cargo las secciones ingresadas hasta el momento
        
        //Llamo a la vista
        $this->load->view('accion_ordenes_trabajo_view', $data);  
    }
    
    function accionPiezasSecundarias() {
        
        $fecha          = $_POST["fecha"];
        $nro_orden      = $_POST["nro_orden"];
        $seccion        = $_POST["seccion"];
        $observaciones  = $_POST["observaciones"];
        
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
            $tipo_accion = 1; //accion piezas secundarias
            $nro_accion = $this->accion_ordenes_trabajo_model->altaAccionSimple($fecha, $nro_orden, $seccion, $observaciones, $tipo_accion);
            //almacen info de orden en variables de session 
            $_SESSION['nro_orden']  = $nro_orden;
            $_SESSION['nro_accion'] = $nro_accion;       
            echo 1;
        }        
        
    }
    
    function accionPiezasAsociadas() {
        
    }    

    function cargoOrdenesTrabajoFiltro() {
        
        $nro_ordenes = $this->accion_ordenes_trabajo_model->cargoNroOrdenes();
        
        $concat = "<option value=''>Seleccione opcion..</option>";
        
        foreach($nro_ordenes as $val) {
            if(isset($_SESSION['seleccion_busqueda'])) {
                if($val == $_SESSION['seleccion_busqueda']) {
                    $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                }else {
                    $concat .= "<option value='".$val."'>".$val."</option>";
                }
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;        
    }
    
    function cargoDatosArma() {
        
        $nro_orden = $_POST['nro_orden'];
        
        if(!empty($nro_orden)) {
            $datos_arma = $this->accion_ordenes_trabajo_model->cargoDatosArma($nro_orden);
        }else {
            $datos_arma[0] = 0;
        }
        
        echo json_encode($datos_arma);
    }
    
    function cargoSecciones() {
        
        $secciones = $this->accion_ordenes_trabajo_model->cargoSecciones();
        
        $concat = "<option> </option>";
        
        foreach($secciones as $val) {
            if($val == $_SESSION['alta_seccion']) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_seccion'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoAcciones() {
        
        $nro_orden = $_POST['nro_orden'];
        
        if(!empty($nro_orden)) {
            $datos_acciones = $this->accion_ordenes_trabajo_model->cargoAcciones($nro_orden);
            /*
                $retorno[] = $row->nro_accion;  0
                $retorno[] = $row->fecha;       1 
                $retorno[] = $row->seccion;     2
                $retorno[] = $row->tipo_accion; 3     
            */   
            
            $concat  = "";
            
            for($i=0; $i<count($datos_acciones); $i=$i+4) {
                
                switch($datos_acciones[$i+3]){
                    
                    case 0:
                        $tipo_accion = "accion simple";
                        break;
                    
                    case 1:
                        $tipo_accion = "accion piezas secundarias";
                        break;
                    
                    case 2:
                        $tipo_accion = "accion piezas asociadas";
                        break;
                }
                
                $concat .= "<tr> 
                            <td style='text-align: center;'>".$datos_acciones[$i]."</td>
                            <td style='text-align: center;'>".$datos_acciones[$i+1]."</td>
                            <td>".$datos_acciones[$i+2]."</td>  
                            <td>".$tipo_accion."</td> 
                            <td style='text-align: center; cursor: pointer;'><img src='".base_url()."images/eye.png' /></td>
                            <td style='text-align: center; cursor: pointer;'><img src='".base_url()."images/edit.png' /></td>
                            <td style='text-align: center; cursor: pointer;'><img src='".base_url()."images/delete.gif' /></td>
                            </tr>";
            }
            
            echo $concat;
            
        }else {
            echo 0;
        }
    }
    
    function validarDatos() {
        
        $fecha          = $_POST["fecha"];
        $nro_orden      = $_POST["nro_orden"];
        $seccion        = $_POST["seccion"];
        $observaciones  = $_POST["observaciones"];
        
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
            $tipo_accion = 0; //accion simple
            $this->accion_ordenes_trabajo_model->altaAccionSimple($fecha, $nro_orden, $seccion, $observaciones, $tipo_accion);
            echo 1;
        }
    }
}

?>
