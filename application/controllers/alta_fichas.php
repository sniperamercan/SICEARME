<?php

class alta_fichas extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_fichas_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 2 y 3 - Usuarios O.C.I y Administradores O.C.I 
        if(!$this->perms->verificoPerfil2() || !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {

        $data['marca'] = "";
        $data['calibre'] = "";
        $data['modelo'] = "";        
        
        $nro_compras = $this->alta_fichas_model->cargoNroCompras();
        
        $data['nro_compras'] = "<option> </option>";
        
        foreach($nro_compras as $val) {
            $data['nro_compras'] .= "<option onclick='cargoNroCatalogos(".$val.")' val='".$val."'>".$val."</option>";
        }
        
        $this->load->view('alta_fichas_view', $data);  
    }
    
    function cargoNroCatalogos() {
        
        $nro_compra = $_POST['nro_compra'];
        
        $nro_catalogos = $this->alta_fichas_model->cargoNroCatalogos($nro_compra);
        
        $concat = "<option> </option>";
        
        foreach($nro_catalogos as $val) {
            $concat .= "<option onclick='cargoInformacion(".$val.")' val='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoInformacion() {
        
        $nro_catalogo = $_POST['nro_catalogo'];
        
        if($this->alta_fichas_model->existeNroCatalogo($nro_catalogo)) {
            $info_catalogo = $this->alta_fichas_model->cargoInformacion($nro_catalogo);
        }
        
        echo json_encode($info_catalogo);
    }
    
    function validarDatos() {
        
        $nro_serie    = $_POST['nro_serie'];
        $nro_compra   = $_POST['nro_compra'];
        $nro_catalogo = $_POST['nro_catalogo'];
        
        $mensjError = array();
        
        if(empty($nro_serie)) {
            $mensjError[] = 1;
        }
        
        if(empty($nro_compra)) {
            $mensjError[] = 2;
        }
        
        if(empty($nro_catalogo)) {
            $mensjError[] = 3;
        }      
        
        $marca   = "";
        $calibre = "";
        $modelo  = "";
        
        if($this->alta_fichas_model->existeNroCatalogo($nro_catalogo)) {
            $info_catalogo = $this->alta_fichas_model->cargoInformacion($nro_catalogo);
            $marca   = $info_catalogo[0];
            $calibre = $info_catalogo[1];
            $modelo  = $info_catalogo[2];
        }
        
        if($this->alta_fichas_model->existeFicha($nro_serie, $marca, $calibre, $modelo)) {
            $mensjError[] = 4;
        }
        
        if(!$this->alta_fichas_model->existeNroCompra($nro_compra)) {
            $mensjError[] = 5;
        }
        
        if(!$this->alta_fichas_model->existeNroCatalogo($nro_catalogo)) {
            $mensjError[] = 6;
        }      
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('nro serie');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nro compra');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('nro catalogo');
                    break;
                
                case 4:
                    echo $this->mensajes->errorExiste('ficha');
                    break;
                
                case 5:
                    echo $this->mensajes->errorNoExiste('nro compra');
                    break;
                
                case 6:
                    echo $this->mensajes->errorNoExiste('nro catalogo');
                    break;                 
            }
        }else {
            $this->alta_fichas_model->agregarFicha($nro_serie, $marca, $calibre, $modelo, $nro_compra, $nro_catalogo);
            echo 1;
        }
        
    }
    
}

?>
