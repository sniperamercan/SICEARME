<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - modificar_catalogos
*/

class modificar_catalogos extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_catalogos_model');
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
        
        if(isset($_SESSION['nro_catalogo']) && !empty($_SESSION['nro_catalogo'])) {
            $nro_catalogo = $_SESSION['nro_catalogo'];
        }else {
            $nro_catalogo = 0;
        }        
        
        //Obtengo datos generales
        $datos_catalogo = $this->modificar_catalogos_model->datosGenerales($_SESSION['nro_catalogo']);
        
        /*
         * $retorno[] = $row->empresa;
         * $retorno[] = $row->pais_origen;
         * $retorno[] = $row->año_fabricacion;
         * $retorno[] = $row->vencimiento; 
        */
        
        //Inicio cargo empresas
        $array_empresas = $this->modificar_catalogos_model->cargoEmpresas();

        $data['empresas'] = "<option> </option>";

        foreach($array_empresas as $val) {
            
            if($val == $datos_catalogo[0]) {
                $data['empresas'] .= "<option selected='selected' value='".$val."'>".$val."</option>";
            }else {
                $data['empresas'] .= "<option value='".$val."'>".$val."</option>";
            }
        }
        //Fin cargo empresas     

        //Inicio cargo paises
        $array_paises = $this->modificar_catalogos_model->cargoPaises();

        $data['paises'] = "<option> </option>";

        foreach($array_paises as $val) {
            
            if($val == $datos_catalogo[1]) {
                $data['paises'] .= "<option selected='selected' value='".$val."'>".$val."</option>";
            }else {
                $data['paises'] .= "<option value='".$val."'>".$val."</option>";
            }
        }
        //Fin cargo paises    
        
        $data['fabricacion'] = $datos_catalogo[2];
        $data['vencimiento'] = $datos_catalogo[3];
        
        $this->load->view('modificar_catalogos_view', $data);  
    }
    
    function armoFormulario() {
        
        if(isset($_SESSION['nro_catalogo']) && !empty($_SESSION['nro_catalogo'])) {
            $nro_catalogo = $_SESSION['nro_catalogo'];
        }else {
            $nro_catalogo = 0;
        }           
        
        $retorno = array();
        /*
         * $retorno[0] = 0 -> entonces el catalogo NO esta asociado a una ficha
         * $retorno[0] = 1 -> entonces el catalogo SI esta asociado a una ficha
        */
        
        //verifico si el nro de catalogo SI esta asociado a una ficha
        if($this->modificar_catalogos_model->catalogoAsociadoFicha($nro_catalogo)) {
            
            $retorno[] = 1;
            $datos_catalogo = $this->modificar_catalogos_model->datosCatalogo($nro_catalogo);
            /*
             * $retorno[] = $row->tipo_arma;
             * $retorno[] = $row->marca;
             * $retorno[] = $row->marca;
             * $retorno[] = $row->modelo;
             * $retorno[] = $row->sistema;
            */
            
            $retorno[] = "<option selected='selected' value='".$datos_catalogo[0]."'>".$datos_catalogo[0]."</option>";
            $retorno[] = "<option selected='selected' value='".$datos_catalogo[1]."'>".$datos_catalogo[1]."</option>";
            $retorno[] = "<option selected='selected' value='".$datos_catalogo[2]."'>".$datos_catalogo[2]."</option>";
            $retorno[] = "<option selected='selected' value='".$datos_catalogo[3]."'>".$datos_catalogo[3]."</option>";
            $retorno[] = "<option selected='selected' value='".$datos_catalogo[4]."'>".$datos_catalogo[4]."</option>";
            
        }else {
            
            $retorno[] = 0;
            $datos_catalogo = $this->modificar_catalogos_model->datosCatalogo($nro_catalogo);
            
            //Inicio cargo tipos armas
            $array_tipos_armas = $this->modificar_catalogos_model->cargoTiposArmas();

            $concat = "<option> </option>";

            foreach($array_tipos_armas as $val) {
                
                if($val == $datos_catalogo[0]) {
                    $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                }else{
                    $concat .= "<option value='".$val."'>".$val."</option>";
                }
            }
            //Fin cargo tipos armas

            $retorno[] = $concat; //almaceno todos los tipos de armas
            
            //Inicio cargo marcas
            $array_marcas = $this->modificar_catalogos_model->cargoMarcas();

            $concat = "<option> </option>";

            foreach($array_marcas as $val) {
                
                if($val == $datos_catalogo[1]) {
                    $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                }else{
                    $concat .= "<option value='".$val."'>".$val."</option>";
                }
            }
            //Fin cargo marcas

            $retorno[] = $concat; //almaceno todos las marcas
            
            //Inicio cargo calibres
            $array_calibres = $this->modificar_catalogos_model->cargoCalibres();

            $concat = "<option> </option>";

            foreach($array_calibres as $val) {
                
                if($val == $datos_catalogo[2]) {
                    $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                }else{
                    $concat .= "<option value='".$val."'>".$val."</option>";
                }
            }
            //Fin cargo calibres

            $retorno[] = $concat; //almaceno todos los calibres
            
            //Inicio cargo modelos
            $array_modelos = $this->modificar_catalogos_model->cargoModelos();

            $concat = "<option> </option>";

            foreach($array_modelos as $val) {
                
                if($val == $datos_catalogo[3]) {
                    $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                }else{
                    $concat .= "<option value='".$val."'>".$val."</option>"; 
                } 
            }
            //Fin cargo modelos

            $retorno[] = $concat; //almaceno todos los modelos
            
            //Inicio cargo sistemas
            $array_sistemas = $this->modificar_catalogos_model->cargoSistemas();

            $concat = "<option> </option>";

            foreach($array_sistemas as $val) {
                
                if($val == $datos_catalogo[4]) {
                    $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                }else{
                    $concat .= "<option value='".$val."'>".$val."</option>";    
                }
            }
            //Fin cargo sistemas
            
            $retorno[] = $concat; //almaceno todos los sistemas
        }
        
       echo json_encode($retorno);
    }
    
    function cargoTiposArmas() {
        
        $tipos_armas = $this->alta_catalogos_model->cargoTiposArmas();
        
        $concat = "<option> </option>";
        
        foreach($tipos_armas as $val) {
            if($_SESSION['alta_tipo_arma'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
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
            $nro_catalogo = $_SESSION['nro_catalogo'];
            $this->modificar_catalogos_model->modificarCatalogo($nro_catalogo, $tipo_arma, $marca, $calibre, $modelo, $sistema, $empresa, $pais_empresa, $fabricacion, $vencimiento);
            $retorno[] = 1;
            $retorno[] = $nro_catalogo;
        }
        
        echo json_encode($retorno);
    }
}

?>