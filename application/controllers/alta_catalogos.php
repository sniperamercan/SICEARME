<?php

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

        //INICIO - cargo tipos armas
        $array_tipos_armas = $this->alta_catalogos_model->cargoTiposArmas();
        
        $data['tipos_armas'] = "<option> </option>";
        
        foreach($array_tipos_armas as $val) {
            $data['tipos_armas'] .= "<option val='".$val."'>".$val."</option>";
        }
        //FIN - cargo tipos armas
        
        //INICIO - cargo marcas
        $array_marcas = $this->alta_catalogos_model->cargoMarcas();
        
        $data['marcas'] = "<option> </option>";
        
        foreach($array_marcas as $val) {
            $data['marcas'] .= "<option val='".$val."'>".$val."</option>";
        }
        //FIN - cargo marcas
        
        //INICIO - cargo calibres
        $array_calibres = $this->alta_catalogos_model->cargoCalibres();
        
        $data['calibres'] = "<option> </option>";
        
        foreach($array_calibres as $val) {
            $data['calibres'] .= "<option val='".$val."'>".$val."</option>";
        }
        //FIN - cargo calibres
        
        //INICIO - cargo modelos
        $array_modelos = $this->alta_catalogos_model->cargoModelos();
        
        $data['modelos'] = "<option> </option>";
        
        foreach($array_modelos as $val) {
            $data['modelos'] .= "<option val='".$val."'>".$val."</option>";
        }
        //FIN - cargo modelos
        
        //INICIO - cargo sistemas
        $array_sistemas = $this->alta_catalogos_model->cargoSistemas();
        
        $data['sistemas'] = "<option> </option>";
        
        foreach($array_sistemas as $val) {
            $data['sistemas'] .= "<option val='".$val."'>".$val."</option>";
        }
        //FIN - cargo sistemas
        
        //INICIO - cargo empresas
        $array_empresas = $this->alta_catalogos_model->cargoEmpresas();
        
        $data['empresas'] = "<option> </option>";
        
        foreach($array_empresas as $val) {
            $data['empresas'] .= "<option val='".$val."'>".$val."</option>";
        }
        //FIN - cargo empresas     
        
        //INICIO - cargo paises
        $array_paises = $this->alta_catalogos_model->cargoPaises();
        
        $data['paises'] = "<option> </option>";
        
        foreach($array_paises as $val) {
            $data['paises'] .= "<option val='".$val."'>".$val."</option>";
        }
        //FIN - cargo paises
        
        $this->load->view('alta_catalogos_view', $data);  
    }
    
    function cargoTiposArmas() {
        
        $tipos_armas = $this->alta_catalogos_model->cargoTiposArmas();
        
        $concat = "<option> </option>";
        
        foreach($tipos_armas as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoMarcas() {
        
        $marcas = $this->alta_catalogos_model->cargoMarcas();
        
        $concat = "<option> </option>";
        
        foreach($marcas as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibres() {
        
        $calibres = $this->alta_catalogos_model->cargoCalibres();
        
        $concat = "<option> </option>";
        
        foreach($calibres as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoModelos() {
        
        $modelos = $this->alta_catalogos_model->cargoModelos();
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoSistemas() {
        
        $sistemas = $this->alta_catalogos_model->cargoSistemas();
        
        $concat = "<option> </option>";
        
        foreach($sistemas as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoEmpresas() {
        
        $empresas = $this->alta_catalogos_model->cargoEmpresas();
        
        $concat = "<option> </option>";
        
        foreach($empresas as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }   
    
    function cargoPaises() {
        
        $paises = $this->alta_catalogos_model->cargoPaises();
        
        $concat = "<option> </option>";
        
        foreach($paises as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }     
    
    function validarDatos() {
        
        $tipo_arma    = $_POST["tipo_arma"];
        $marca        = $_POST["marca"];
        $calibre      = $_POST["calibre"];
        $modelo       = $_POST["modelo"];
        $sistema      = $_POST["sistema"];
        $empresa      = $_POST["empresa"];
        $pais_empresa = $_POST["pais_empresa"];
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
        }
        
        echo json_encode($retorno);
    }
}

?>