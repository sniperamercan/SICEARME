<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - alta_ordenes_trabajo
*/

class alta_ordenes_trabajo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_ordenes_trabajo_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 6 y 7 - Usuario Taller de armamento y Administrador Taller de armamento
        if(!$this->perms->verificoPerfil6() && !$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        //Cargo las unidades
        $unidades = $this->alta_ordenes_trabajo_model->cargoUnidades();
        
        $data['unidades'] = "<option> </option>";
        
        for($i=0; $i < count($unidades); $i=$i+2) {
            $data['unidades'] .= "<option value='".$unidades[$i]."'>".$unidades[$i+1]."</option>";
        }
        //Fin cargo unidades
        
        
        //Cargo nro de series de armamentos que esten en deposito inicial
        $nro_series = $this->alta_ordenes_trabajo_model->cargoNroSeries();
        
        $aux = '""';
        
        $data['nro_series'] = "<option> </option>";
        
        foreach($nro_series as $val) {
            $aux = '"'.$val.'"';
            $data['nro_series'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo nro de series de armamento en deposito inicial
        
        $this->load->view('alta_ordenes_trabajo_view', $data); 
    }
    
    function cargoDatos() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $datos = $this->alta_ordenes_trabajo_model->cargoDatos($nro_serie, $marca, $calibre, $modelo);
        
        /*
         * $datos[0] - tipo_arma 1 
         * $datos[1] - sistema   2
         */
        
        for($i=0; $i<count($datos); $i=$i+2) {
            $tipo_sistema[] = $datos[$i];
            $tipo_sistema[] = $datos[$i+1];
        }
        
        echo json_encode($tipo_sistema);
    }
    
    function cargoMarcas() {
        
        $nro_serie = $_POST['nro_serie'];
       
        $marcas = $this->alta_ordenes_trabajo_model->cargoMarcas($nro_serie);
       
        $concat = "<option> </option>";
        
        foreach($marcas as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibres() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        
        $calibres = $this->alta_ordenes_trabajo_model->cargoCalibres($nro_serie, $marca);
        
        $concat = "<option> </option>";
        
        foreach($calibres as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
     function cargoModelos() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        
        $modelos = $this->alta_ordenes_trabajo_model->cargoModelos($nro_serie, $marca, $calibre);
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    } 
    
    function cargoFichasFiltro() {
        
        $nro_serie = $_SESSION['seleccion_busqueda'];
        $marca     = $_SESSION['seleccion_busqueda1'];
        $calibre   = $_SESSION['seleccion_busqueda2'];
        $modelo    = $_SESSION['seleccion_busqueda3'];
        
        if(empty($nro_serie)) {
            //Cargo nro de series de armamentos que esten en deposito inicial
            $nro_series_array = $this->alta_actas_alta_model->cargoNroSeries();

            $aux = '""';
            $nro_series  = "<option> </option>";

            foreach($nro_series_array as $val) {
                $aux = '"'.$val.'"';
                $nro_series .= "<option value='".$val."'>".$val."</option>";
            }
            //Fin cargo nro de series de armamento en deposito inicial            
        }else {
            $nro_series  = "<option> </option>";
            $nro_series .= "<option selected='selected' value='".$nro_serie."'>".$nro_serie."</option>";
        }

        $marcas  = "<option> </option>";
        $marcas .= "<option selected='selected' value='".$marca."'>".$marca."</option>";
        
        $calibres  = "<option> </option>";
        $calibres .= "<option selected='selected' value='".$calibre."'>".$calibre."</option>";
        
        $modelos  = "<option> </option>";
        $modelos .= "<option selected='selected' value='".$modelo."'>".$modelo."</option>";        
        
        //Retorno los datos
        $retorno = array();
        $retorno[] = $nro_series;
        $retorno[] = $marcas;
        $retorno[] = $calibres;
        $retorno[] = $modelos;
        
        $datos = $this->alta_ordenes_trabajo_model->cargoDatos($nro_serie, $marca, $calibre, $modelo);
        
        $retorno[] = $datos[0]; //tipo_arma
        $retorno[] = $datos[1]; //sistema
        
        echo json_encode($retorno);        
    }    
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $fecha         = preg_replace($patterns, '', $_POST["fecha"]);
        $unidad        = preg_replace($patterns, '', $_POST["unidad"]);
        $nro_serie     = preg_replace($patterns, '', $_POST["nro_serie"]);
        $marca         = preg_replace($patterns, '', $_POST["marca"]);
        $calibre       = preg_replace($patterns, '', $_POST["calibre"]);
        $modelo        = preg_replace($patterns, '', $_POST["modelo"]);
        $observaciones = preg_replace($patterns, '', $_POST["observaciones"]);
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($fecha)) {
            $mensjError[] = 1;
        }
        
        if(empty($unidad)) {
            $mensjError[] = 2;
        }
        
        if(empty($nro_serie)) {
            $mensjError[] = 3;
        }
        
        if(empty($marca)) {
            $mensjError[] = 4;
        }        
        
        if(empty($calibre)) {
            $mensjError[] = 5;
        }        

        if(empty($modelo)) {
            $mensjError[] = 6;
        }        

        if(empty($observaciones)) {
            $mensjError[] = 7;
        }        
       
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('unidad');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('nro serie');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('marca');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorVacio('calibre');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorVacio('modelo');
                    break;
                
                case 7:
                    $retorno[] = $this->mensajes->errorVacio('observaciones');
                    break;
            }
        }else {
            $nro_orden = $this->alta_ordenes_trabajo_model->altaOrdenTrabajo($fecha, $unidad, $nro_serie, $marca, $calibre, $modelo, $observaciones);
            $retorno[] = 1;
            $retorno[] = $nro_orden;
        }
        
        echo json_encode($retorno);
    }
}

?>