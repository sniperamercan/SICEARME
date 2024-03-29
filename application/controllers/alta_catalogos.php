<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - alta_catalogos
*/

class alta_catalogos extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_catalogos_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 2 y 3 - Usuarios O.C.I y Administradores O.C.I 
        if(!$this->perms->verificoPerfil2() && !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {

        if(!isset($_SESSION['crear_catalogo'])) {
            $_SESSION['crear_catalogo'] = false;
        }
        
        $_SESSION['alta_nro_catalogo'] = '';
        
        //Inicio cargo tipos armas
        $array_tipos_armas = $this->alta_catalogos_model->cargoTiposArmas();
        
        $data['tipos_armas'] = "<option> </option>";
        
        foreach($array_tipos_armas as $val) {
            $data['tipos_armas'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo tipos armas
        
        //Inicio cargo marcas
        $array_marcas = $this->alta_catalogos_model->cargoMarcas();
        
        $data['marcas'] = "<option> </option>";
        
        foreach($array_marcas as $val) {
            $data['marcas'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo marcas
        
        //Inicio cargo calibres
        $array_calibres = $this->alta_catalogos_model->cargoCalibres();
        
        $data['calibres'] = "<option> </option>";
        
        foreach($array_calibres as $val) {
            $data['calibres'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo calibres
        
        //Inicio cargo modelos
        $array_modelos = $this->alta_catalogos_model->cargoModelos();
        
        $data['modelos'] = "<option> </option>";
        
        foreach($array_modelos as $val) {
            $data['modelos'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo modelos
        
        //Inicio cargo sistemas
        $array_sistemas = $this->alta_catalogos_model->cargoSistemas();
        
        $data['sistemas'] = "<option> </option>";
        
        foreach($array_sistemas as $val) {
            $data['sistemas'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo sistemas
        
        //Inicio cargo empresas
        $array_empresas = $this->alta_catalogos_model->cargoEmpresas();
        
        $data['empresas'] = "<option> </option>";
        
        foreach($array_empresas as $val) {
            $data['empresas'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo empresas     
        
        //Inicio cargo paises
        $array_paises = $this->alta_catalogos_model->cargoPaises();
        
        $data['paises'] = "<option> </option>";
        
        foreach($array_paises as $val) {
            $data['paises'] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo paises
        
        $this->load->view('alta_catalogos_view', $data); 
        
        unset($_SESSION['crear_catalogo']);
    }
    
    function cargoTiposArmas() {
        
        $tipos_armas = $this->alta_catalogos_model->cargoTiposArmas();
        
        $concat = "<option> </option>";
        
        foreach($tipos_armas as $val) {
            if($_SESSION['alta_tipo_arma'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_tipo_arma'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoMarcas() {
        
        $marcas = $this->alta_catalogos_model->cargoMarcas();
        
        $concat = "<option> </option>";
        
        foreach($marcas as $val) {
            if($_SESSION['alta_marca'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_marca'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoCalibres() {
        
        $calibres = $this->alta_catalogos_model->cargoCalibres();
        
        $concat = "<option> </option>";
        
        foreach($calibres as $val) {
            if($_SESSION['alta_calibre'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_calibre'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoModelos() {
        
        $modelos = $this->alta_catalogos_model->cargoModelos();
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            if($_SESSION['alta_modelo'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_modelo'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoSistemas() {
        
        $sistemas = $this->alta_catalogos_model->cargoSistemas();
        
        $concat = "<option> </option>";
        
        foreach($sistemas as $val) {
            if($_SESSION['alta_sistema'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_sistema'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoEmpresas() {
        
        $empresas = $this->alta_catalogos_model->cargoEmpresas();
        
        $concat = "<option> </option>";
        
        foreach($empresas as $val) {
            if($_SESSION['alta_empresa'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_empresa'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }   
    
    function cargoPaises() {
        
        $paises = $this->alta_catalogos_model->cargoPaises();
        
        $concat = "<option> </option>";
        
        foreach($paises as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }     
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $tipo_arma    = preg_replace($patterns, '', $_POST["tipo_arma"]);
        $marca        = preg_replace($patterns, '', $_POST["marca"]);
        $calibre      = preg_replace($patterns, '', $_POST["calibre"]);
        $modelo       = preg_replace($patterns, '', $_POST["modelo"]);
        $sistema      = preg_replace($patterns, '', $_POST["sistema"]);
        $empresa      = preg_replace($patterns, '', $_POST["empresa"]);
        $pais_empresa = preg_replace($patterns, '', $_POST["pais_empresa"]);
        $fabricacion  = $_POST["fabricacion"];
        $vencimiento  = $_POST["vencimiento"];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($tipo_arma)) {
            $mensjError[] = 1;
        }
        
        if(empty($marca)) {
            $mensjError[] = 2;
        }
        
        if(empty($calibre)) {
            $mensjError[] = 3;
        }
        
        if(empty($modelo)) {
            $mensjError[] = 4;
        }        
        
        if(empty($sistema)) {
            $mensjError[] = 5;
        }        

        if(empty($empresa)) {
            $mensjError[] = 6;
        }        

        if(empty($pais_empresa)) {
            $mensjError[] = 7;
        }        

        if(empty($fabricacion)) {
            $mensjError[] = 8;
        }        

        if(empty($vencimiento)) {
            $mensjError[] = 9;
        }          
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('tipo arma');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('marca');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('calibre');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('modelo');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorVacio('sistema');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorVacio('empresa');
                    break;
                
                case 7:
                    $retorno[] = $this->mensajes->errorVacio('pais empresa');
                    break;
               
                case 8:
                    $retorno[] = $this->mensajes->errorVacio('fabricacion');
                    break;
                
                case 9:
                    $retorno[] = $this->mensajes->errorVacio('vencimiento');
                    break;
            }
        }else {
            $nro_interno = $this->alta_catalogos_model->altaCatalogo($tipo_arma, $marca, $calibre, $modelo, $sistema, $empresa, $pais_empresa, $fabricacion, $vencimiento);
            $retorno[] = 1;
            $retorno[] = $nro_interno;
            $_SESSION['alta_nro_catalogo'] = $nro_interno;
        }
        
        echo json_encode($retorno);
    }
}

?>