<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - accion_mutacion_armamento
*/

class accion_mutacion_armamento extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accion_mutacion_armamento_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 7 - Administradores taller de armamento 
        if(!$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $_SESSION['nro_orden']  = "";
        
        //cargo los nro de ordenes ingresados hasta el momento
        $nro_ordenes = $this->accion_mutacion_armamento_model->cargoNroOrdenes();
        
        $data['nro_ordenes'] = "<option value=''>Seleccione opcion..</option>";
        
        foreach($nro_ordenes as $val) {
            $data['nro_ordenes'] .= "<option value='".$val."'>".$val."</option>";
        }
        //fin cargo nro de ordenes ingresados hasta el momento
        
        //cargo las secciones ingresadas hasta el momento
        $secciones = $this->accion_mutacion_armamento_model->cargoSecciones();
        
        $data['secciones'] = "<option value=''>Seleccione opcion..</option>";
        
        foreach($secciones as $val) {
            $data['secciones'] .= "<option value='".$val."'>".$val."</option>";
        }
        //fin cargo las secciones ingresadas hasta el momento
        
        //Llamo a la vista
        $this->load->view('accion_mutacion_armamento_view', $data);  
    }

    function cargoPiezasArmamentoFiltro() {

        if( !empty($_SESSION['seleccion_busqueda']) ) {
            $nro_pieza = $_SESSION['seleccion_busqueda'];
        }else {
            $nro_pieza = 0;
        }
        
        echo $nro_pieza;
    }     
    
    function cargoOrdenesTrabajoFiltro() {
        
        $nro_ordenes = $this->accion_mutacion_armamento_model->cargoNroOrdenes();
        
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
            $datos_arma = $this->accion_mutacion_armamento_model->cargoDatosArma($nro_orden);
        }else {
            $datos_arma[0] = 0;
        }
        
        echo json_encode($datos_arma);
    }
    
    function cargoSecciones() {
        
        $secciones = $this->accion_mutacion_armamento_model->cargoSecciones();
        
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

    function cargoRepuestosFiltro() {
        
        $datos = array();
        
        if( !empty($_SESSION['seleccion_busqueda']) ) {
            $datos[0] = $_SESSION['seleccion_busqueda'];
            $datos[1] = $_SESSION['seleccion_busqueda1'];
            $datos[2] = $_SESSION['seleccion_busqueda2'];
            $datos[3] = $_SESSION['seleccion_busqueda3'];
        }else {
            $datos[0] = 0;
        }
        
        echo json_encode($datos);
    }    

    function cargoNroOrdenTrabajo() {
        if(!empty($_POST['nro_orden'])) {
            $_SESSION['nro_orden'] = $_POST['nro_orden'];
            echo 1;
        }else{
            echo 0;
        }
    }
    
    function busquedaRepuestos() {
        
        $nro_orden = $_SESSION['nro_orden'];
        $nro_pieza = $_POST['nro_pieza'];
        
        $tipo_pieza = $this->accion_mutacion_armamento_model->cargoTipoPieza($nro_orden, $nro_pieza);
        
        $_SESSION['tipo_pieza'] = $tipo_pieza;
    }    
    
    function validarDatos() {
        
        $patterns = array();
        
        $patterns[] = '/"/';
        $patterns[] = "/'/";        
        
        $patterns[] = '/\&/';
        
        $patterns[] = '/{/';
        
        $patterns[] = '/}/';
        
        $patterns[] = '/|/';        
        
        $fecha           = $_POST["fecha"];
        $nro_orden       = $_POST["nro_orden"];
        $seccion         = $_POST["seccion"];
        $nro_pieza_nueva = $_POST["nro_pieza_nueva"];
        
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
         
        if(empty($nro_pieza_nueva)) {
            $mensaje_error[] = 5;
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
                
                case 5:
                    echo $this->mensajes->errorVacio('nro pieza nueva');
                    break;                
            }
        }else {
            
            //obtengo datos del armamento y de su ficha
            $datos = $this->accion_mutacion_armamento_model->cargoDatosArma($nro_orden);
            
            /*
                $datos[] = $row->nro_serie;
                $datos[] = $row->marca;
                $datos[] = $row->calibre;
                $datos[] = $row->modelo;
                $datos[] = $row->nro_interno_compra;
                $datos[] = $row->nro_interno_catalogo;
             */
            
            //datos de la ficha
            $nro_serie            = $datos[0];
            $marca                = $datos[1];
            $calibre              = $datos[2];
            $modelo               = $datos[3];
            $nro_interno_compra   = $datos[4];
            $nro_interno_catalogo = $datos[5];
            
            $datos_accesorios = array();
            
            if($this->accion_mutacion_armamento_model->hayAccesoriosArma($nro_serie, $marca, $calibre, $modelo)) {
                //obtengo todos los accesorios de esa ficha
                $datos_accesorios = $this->accion_mutacion_armamento_model->cargoAccesoriosArma($nro_serie, $marca, $calibre, $modelo);                
            }
            
            if(!$this->accion_mutacion_armamento_model->existeFicha($nro_pieza_nueva, $marca, $calibre, $modelo)) {
                $this->accion_mutacion_armamento_model->accionMutacionArmamento($fecha, $nro_orden, $seccion, $observaciones, $nro_pieza_nueva, $nro_serie, $marca, $calibre, $modelo, $nro_interno_compra, $nro_interno_catalogo, $datos_accesorios);
                echo 1;
            }else {
                echo $this->mensajes->errorExiste('ficha');
            } 
        }
    }
}

?>
