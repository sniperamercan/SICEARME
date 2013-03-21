<?php

class alta_actas_alta extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_actas_alta_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 4 y 5 - Usuarios O.C.I y Administradores O.C.I 
        if(!$this->perms->verificoPerfil4() || !$this->perms->verificoPerfil5()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        //cargo las unidades
        $unidades = $this->alta_actas_alta_model->cargoUnidades();
        
        $data['unidades'] = "<option> </option>";
        
        for($i=0; $i < count($unidades); $i=$i+2) {
            $data['unidades'] .= "<option val='".$unidades[$i]."'>".$unidades[$i+1]."</option>";
        }
        //fin cargo unidades
        
        //cargo nro de series de armamentos que esten en deposito inicial
        $nro_series = $this->alta_actas_alta_model->cargoNroSeries();
        
        $aux = '""';
        $data['nro_series'] = "<option onclick='cargoMarcas(".$aux.");'> </option>";
        
        foreach($nro_series as $val) {
            $aux = '"'.$val.'"';
            $data['nro_series'] .= "<option onclick='cargoMarcas(".$aux.");' val='".$val."'>".$val."</option>";
        }
        //fin cargo nro de series de armamento en deposito inicial
        
        //cargo nro de series de armamentos que esten en deposito inicial de accesorios
        $nro_series_accesorios = $this->alta_actas_alta_model->cargoNroSeriesAccesorios();
        
        $aux = '""';
        $data['nro_series_accesorios'] = "<option onclick='cargoMarcasAccesorios(".$aux.");'> </option>";
        
        foreach($nro_series_accesorios as $val) {
            $aux = '"'.$val.'"';
            $data['nro_series_accesorios'] .= "<option onclick='cargoMarcasAccesorios(".$aux.");' val='".$val."'>".$val."</option>";
        }
        //fin cargo nro de series de armamento en deposito inicial        
        
        $this->load->view('alta_actas_alta_view', $data);  
    }
    
    function cargoMarcas() {
        
        $nro_serie     = $_POST['nro_serie'];
        $aux_nro_serie = '"'.$nro_serie.'"';
        
        $marcas = $this->alta_actas_alta_model->cargoMarcas($nro_serie);
        
        $aux = '""';
        $concat = "<option onclick='cargoCalibres(".$aux.",".$aux.");'> </option>";
        
        foreach($marcas as $val) {
            $aux_marca = '"'.$val.'"';
            $concat .= "<option onclick='cargoCalibres(".$aux_nro_serie.",".$aux_marca.");' val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibres() {
        
        $nro_serie     = $_POST['nro_serie'];
        $aux_nro_serie = '"'.$nro_serie.'"';
        
        $marca     = $_POST['marca'];
        $aux_marca = '"'.$marca.'"';            
        
        $calibres = $this->alta_actas_alta_model->cargoCalibres($nro_serie, $marca);
        
        $aux = '""';
        $concat = "<option onclick='cargoModelos(".$aux.",".$aux.",".$aux.");'> </option>";
        
        foreach($calibres as $val) {
            $aux_calibre = '"'.$val.'"';
            $concat .= "<option onclick='cargoModelos(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.");' val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
     function cargoModelos() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        
        $modelos = $this->alta_actas_alta_model->cargoModelos($nro_serie, $marca, $calibre);
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }   
    
    function cargoMarcasAccesorios() {
        
        $nro_serie     = $_POST['nro_serie'];
        $aux_nro_serie = '"'.$nro_serie.'"';
        
        $marcas = $this->alta_actas_alta_model->cargoMarcasAccesorios($nro_serie);
        
        $aux = '""';
        $concat = "<option onclick='cargoCalibresAccesorios(".$aux.",".$aux.");'> </option>";
        
        foreach($marcas as $val) {
            $aux_marca = '"'.$val.'"';
            $concat .= "<option onclick='cargoCalibresAccesorios(".$aux_nro_serie.",".$aux_marca.");' val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibresAccesorios() {
        
        $nro_serie     = $_POST['nro_serie'];
        $aux_nro_serie = '"'.$nro_serie.'"';
        
        $marca     = $_POST['marca'];
        $aux_marca = '"'.$marca.'"';            
        
        $calibres = $this->alta_actas_alta_model->cargoCalibresAccesorios($nro_serie, $marca);
        
        $aux = '""';
        $concat = "<option onclick='cargoModelosAccesorios(".$aux.",".$aux.",".$aux.");'> </option>";
        
        foreach($calibres as $val) {
            $aux_calibre = '"'.$val.'"';
            $concat .= "<option onclick='cargoModelosAccesorios(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.");' val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
     function cargoModelosAccesorios() {
        
        $nro_serie     = $_POST['nro_serie'];
        $aux_nro_serie = '"'.$nro_serie.'"';
        
        $marca     = $_POST['marca'];
        $aux_marca = '"'.$marca.'"';     
        
        $calibre     = $_POST['calibre'];
        $aux_calibre = '"'.$calibre.'"';     
        
        $modelos = $this->alta_actas_alta_model->cargoModelosAccesorios($nro_serie, $marca, $calibre);
        
        $aux = '""';
        $concat = "<option onclick='cargoNroAccesorios(".$aux.",".$aux.",".$aux.");'> </option>";
        
        foreach($modelos as $val) {
            $aux_modelo = '"'.$val.'"';
            $concat .= "<option onclick='cargoNroAccesorios(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    } 
    
     function cargoNroAccesorios() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $modelos = $this->alta_actas_alta_model->cargoNroAccesorios($nro_serie, $marca, $calibre, $modelo);
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
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
