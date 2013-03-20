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
    
    function cargoComprasFiltro() {
        
        $nro_compra   = $_SESSION['seleccion_busqueda'];
        $nro_catalogo = $_SESSION['seleccion_busqueda1'];
        
        //cargo los numeros de compras filtrados por el seleccionado
        $nro_compras = $this->alta_fichas_model->cargoNroCompras();
        
        $compras = "<option> </option>";
        
        foreach($nro_compras as $val) {
            
            if($nro_compra == $val) {
                $compras .= "<option onclick='cargoNroCatalogos(".$val.")' selected='selected' val='".$val."'>".$val."</option>";
            }else{
                $compras .= "<option onclick='cargoNroCatalogos(".$val.")' val='".$val."'>".$val."</option>";
            }
        }
        
        //cargo los numeros de catalogos filtrados por el seleccionado
        $nro_catalogos = $this->alta_fichas_model->cargoNroCatalogos($nro_compra);
        
        $catalogos = "<option> </option>";
        
        foreach($nro_catalogos as $val) {
            if($nro_catalogo == $val) {
                $catalogos .= "<option onclick='cargoInformacion(".$val.")' selected='selected' val='".$val."'>".$val."</option>";
            }else{
                $catalogos .= "<option onclick='cargoInformacion(".$val.")' val='".$val."'>".$val."</option>";
            }
        }
        
        //retorno los datos
        $info_catalogos = $this->cargoInformacionArray($nro_catalogo);
        $retorno = array();
        $retorno[] = $compras;
        $retorno[] = $catalogos;
        $retorno[] = $info_catalogos[0];
        $retorno[] = $info_catalogos[1];
        $retorno[] = $info_catalogos[2];
        
        echo json_encode($retorno);
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
    
    function cargoInformacionArray($nro_catalogo) {
        
        if($this->alta_fichas_model->existeNroCatalogo($nro_catalogo)) {
            $info_catalogo = $this->alta_fichas_model->cargoInformacion($nro_catalogo);
        }
        
        return $info_catalogo;
    }    
    
    function agregarAccesorio() {
        
        $nro_accesorio         = $_POST['nro_accesorio'];
        $tipo_accesorio        = $_POST['tipo_accesorio'];
        $descripcion_accesorio = $_POST['descripcion_accesorio'];
        $nro_catalogo          = $_POST['nro_catalogo'];
        $nro_serie             = $_POST['nro_serie'];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($nro_accesorio)) {
            $mensjError[] = 1;
        }
        
        if(empty($tipo_accesorio)) {
            $mensjError[] = 2;
        }
        
        if(empty($descripcion_accesorio)) {
            $mensjError[] = 3;
        }
        
        if(empty($nro_catalogo)) {
            $mensjError[] = 4;
        }else {
            
            $info_catalogos = $this->cargoInformacionArray($nro_catalogo);
            $marca   = $info_catalogos[0];
            $calibre = $info_catalogos[1];
            $modelo  = $info_catalogos[2];
            
            if(!$this->alta_compras_model->existeAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio)) {
                $mensjError[] = 5;
            }            
        }
        
        //verifico que el nro de catalogo no exista ya en el listado
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['accesorios']) && !$encontre) {
            
            if($_SESSION['accesorios'][$i] == $nro_accesorio) {
                $encontre = true;
            }
            
            $i=$i+3; 
        }
        
        if($encontre) {
           $mensjError[] = 6; 
        }
        
        if(count($mensjError) > 0) {
            
            switch ($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('nro accesorio');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('tipo accesorio');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('descripcion accesorio');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('nro catalogo');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorExiste('accesorio');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorAccesorioExiste();
                    break;
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['accesorios'][] = $nro_accesorio;
            $_SESSION['accesorios'][] = $tipo_accesorio;
            $_SESSION['accesorios'][] = $descripcion_accesorio;
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$nro_accesorio."</td> <td>".$tipo_accesorio."</td> <td>".$descripcion_accesorio."</td> <td><img style='cursor: pointer;' onclick='anularCatalogo(".$catalogo.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";
            
            $retorno[] = $concat;
        }
        
        echo json_encode($retorno);        
    }
    
    function agregarPieza() {

        $nro_pieza         = $_POST['nro_pieza'];
        $tipo_pieza        = $_POST['tipo_pieza'];
        $descripcion_pieza = $_POST['descripcion_pieza'];
        $nro_catalogo      = $_POST['nro_catalogo'];
        $nro_serie         = $_POST['nro_serie'];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($nro_pieza)) {
            $mensjError[] = 1;
        }
        
        if(empty($tipo_pieza)) {
            $mensjError[] = 2;
        }
        
        if(empty($descripcion_pieza)) {
            $mensjError[] = 3;
        }
        
        if(empty($nro_catalogo)) {
            $mensjError[] = 4;
        }else {
            
            $info_catalogos = $this->cargoInformacionArray($nro_catalogo);
            $marca   = $info_catalogos[0];
            $calibre = $info_catalogos[1];
            $modelo  = $info_catalogos[2];
            
            if(!$this->alta_compras_model->existePieza($nro_serie, $marca, $calibre, $modelo, $nro_pieza)) {
                $mensjError[] = 5;
            }            
        }
        
        //verifico que el nro de catalogo no exista ya en el listado
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['piezas']) && !$encontre) {
            
            if($_SESSION['piezas'][$i] == $nro_pieza) {
                $encontre = true;
            }
            
            $i=$i+3; 
        }
        
        if($encontre) {
           $mensjError[] = 6; 
        }
        
        if(count($mensjError) > 0) {
            
            switch ($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('nro pieza');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('tipo pieza');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('descripcion pieza');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('nro catalogo');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorExiste('accesorio');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorAccesorioExiste();
                    break;
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['piezas'][] = $nro_pieza;
            $_SESSION['piezas'][] = $tipo_pieza;
            $_SESSION['piezas'][] = $descripcion_pieza;
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$nro_pieza."</td> <td>".$tipo_pieza."</td> <td>".$descripcion_pieza."</td> <td><img style='cursor: pointer;' onclick='anularCatalogo(".$catalogo.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";
            
            $retorno[] = $concat;
        }
        
        echo json_encode($retorno);           
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
