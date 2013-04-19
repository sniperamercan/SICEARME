<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - accion_piezas_asociadas
*/

class accion_piezas_asociadas extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accion_piezas_asociadas_model');
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
        
        //Inicializo variable de session de catalogos vacia
        if(isset($_SESSION['catalogos'])) {
            unset($_SESSION['catalogos']);
        }
        
        $_SESSION['catalogos'] = array();
        
        //Cargo paises
        $array_paises = $this->accion_piezas_asociadas_model->cargoPaises();
        
        $data['paises'] = "<option> </option>";
        
        foreach($array_paises as $val) {
            $data['paises'] .= "<option value='".$val."'>".$val."</option>";
        }
        
        //Cargo catalogos
        $catalogos = $this->accion_piezas_asociadas_model->cargoCatalogos();
        
        $data['catalogos'] = "<option> </option>";
        
        foreach($catalogos as $val) {
            $data['catalogos'] .= "<option value='".$val."'>".$val."</option>";
        }
        
        //Cargo empresas
        $empresas = $this->accion_piezas_asociadas_model->cargoEmpresas();
        
        $data['empresas'] = "<option> </option>";
        
        foreach($empresas as $val) {
            $data['empresas'] .= "<option value='".$val."'>".$val."</option>";
        }       
        
        //Llamo a la vista
        $this->load->view('accion_piezas_asociadas_view', $data);  
    }
    
    function cargoEmpresas() {
        
        $empresas = $this->alta_compras_model->cargoEmpresas();
        
        $concat = "<option> </option>";
        
        foreach($empresas as $val) {
            if($val == $_SESSION['alta_empresa']) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_empresa'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function crearCatalogo() {
        $_SESSION['crear_catalogo'] = true;
    }
    
    function cargoCatalogos() {
        
        $catalogos = $this->alta_compras_model->cargoCatalogos();
        
        $concat = "<option> </option>";
        
        foreach($catalogos as $val) {
            if($_SESSION['alta_nro_catalogo'] == $val) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_nro_catalogo'] = "";
            }else{
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoCatalogosFiltro() {
        
        $catalogos = $this->alta_compras_model->cargoCatalogos();
        
        $concat = "<option> </option>";
        
        foreach($catalogos as $val) {
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
    
    function agregarCatalogos() {
        
        $catalogo         = $_POST['catalogo'];
        $cant_total_armas = $_POST['cant_total_armas'];
        $costo_total      = $_POST['costo_total'];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($catalogo)) {
            $mensjError[] = 1;
        }
        
        if(empty($cant_total_armas)) {
            $mensjError[] = 2;
        }
        
        if(empty($costo_total)) {
            $mensjError[] = 3;
        }
        
        if(!$this->alta_compras_model->existeCatalogo($catalogo)) {
            $mensjError[] = 4;
        }
        
        if(!$this->form_validation->numeric($cant_total_armas)) {
            $mensjError[] = 5;
        }
        
        if(!$this->form_validation->numeric($costo_total)) {
            $mensjError[] = 6;
        }
        
        //Verifico que el nro de catalogo no exista ya en el listado
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['catalogos']) && !$encontre) {
            
            if($_SESSION['catalogos'][$i] == $catalogo) {
                $encontre = true;
            }
            
            $i=$i+3; 
        }
        
        if($encontre) {
           $mensjError[] = 7; 
        }
        
        
        if(count($mensjError) > 0) {
            
            switch ($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('catalogo');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('cant total armas');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('costo');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorNoExiste('el catalogo '.$catalogo);
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorNumerico('cant total armas');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorNumerico('costo');
                    break;
                
                case 7:
                    $retorno[] = $this->mensajes->errorCatalogoExiste();
                    break;
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['catalogos'][] = $catalogo;
            $_SESSION['catalogos'][] = $cant_total_armas;
            $_SESSION['catalogos'][] = $costo_total;
            
            $datos_catalogo = $this->alta_compras_model->datosCatalogo($catalogo);
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$catalogo."</td> <td>".$datos_catalogo['tipo_arma']."</td> <td>".$datos_catalogo['marca']."</td> <td>".$datos_catalogo['modelo']."</td> <td>".$datos_catalogo['calibre']."</td> <td>".$datos_catalogo['sistema']."</td> <td style='text-align: center;'>".$cant_total_armas."</td> <td style='text-align: center;'>".$costo_total."</td> <td><img style='cursor: pointer;' onclick='anularCatalogo(".$catalogo.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";
            
            $totales = "<tr class='total'> 
                            <td>".$this->obtenerCantidadArmasTotales()."</td> <td>".$this->obterPrecioTotal()."</td>
                        </tr>";
            
            $retorno[] = $concat;
            $retorno[] = $totales;
        }
        
        echo json_encode($retorno);
    }
    
    function anularCatalogo() {
        
        $nro_catalogo = $_POST['nro_catalogo'];
        
        $retorno = array();
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['catalogos']) && !$encontre) {
            
            if($_SESSION['catalogos'][$i] == $nro_catalogo) {
                $encontre = true;
                unset($_SESSION['catalogos'][$i]);   //nro_interno
                unset($_SESSION['catalogos'][$i+1]); //cantidad_armas
                unset($_SESSION['catalogos'][$i+2]); //precio
                $_SESSION['catalogos'] = array_values($_SESSION['catalogos']); //reordeno el array
            }
            $i=$i+3;
        }
        
        if($encontre) {
            
            $concat = '';
            
            for($i=0; $i<count($_SESSION['catalogos']); $i=$i+3) {
                
                $datos_catalogo = $this->alta_compras_model->datosCatalogo($_SESSION['catalogos'][$i]);
                
                $concat .= "<tr> 
                                <td style='text-align: center;'>".$_SESSION['catalogos'][$i]."</td> <td>".$datos_catalogo['tipo_arma']."</td> <td>".$datos_catalogo['marca']."</td> <td>".$datos_catalogo['modelo']."</td> <td>".$datos_catalogo['calibre']."</td> <td>".$datos_catalogo['sistema']."</td> <td style='text-align: center;'>".$_SESSION['catalogos'][$i+1]."</td> <td style='text-align: center;'>".$_SESSION['catalogos'][$i+2]."</td> <td><img style='cursor: pointer;' onclick='anularCatalogo(".$_SESSION['catalogos'][$i].");' src='".  base_url()."images/delete.gif'/></td>
                           </tr>";  
                
                $totales = "<tr class='total'> 
                                <td>".$this->obtenerCantidadArmasTotales()."</td> <td>".$this->obterPrecioTotal()."</td>
                            </tr>";                
            }
            if(count($_SESSION['catalogos']) == 0) {
                $retorno[] = 0;
            }else {
                $retorno[] = 1;
                $retorno[] = $concat;
                $retorno[] = $totales;
            }
        }else {
            $retorno[] = 0;
        }
        
        echo json_encode($retorno);
    }
    
    function obterPrecioTotal() {

        $costo_total = 0;
        
        if(count($_SESSION['catalogos']) > 0) {

            for($i=0; $i<count($_SESSION['catalogos']); $i=$i+3) {
                $costo_total = $costo_total + $_SESSION['catalogos'][$i+2];
            }
        }
        
        return $costo_total;
    }
    
    function obtenerCantidadArmasTotales() {
        
        $cant_armas_total = 0;
        
        if(count($_SESSION['catalogos']) > 0) {

            for($i=0; $i<count($_SESSION['catalogos']); $i=$i+3) {
                $cant_armas_total = $cant_armas_total + $_SESSION['catalogos'][$i+1];
            }
        }
        return $cant_armas_total;        
    }
    
    
    function validarDatos() {
        
        $nro_compra   = $_POST["nro_compra"];
        $fecha        = $_POST["fecha"];
        $empresa      = $_POST["empresa"];
        $pais_empresa = $_POST["pais_empresa"];
        $descripcion  = $_POST["descripcion"];
        $modalidad    = $_POST["modalidad"];
        
        $mensjError = array();
        
        if(empty($nro_compra)) {
            $mensjError[] = 1;
        }
        
        if(empty($fecha)) {
            $mensjError[] = 2;
        }
        
        if(empty($empresa)) {
            $mensjError[] = 3;
        }
        
        if(empty($pais_empresa)) {
            $mensjError[] = 4;
        }        
        
        if(empty($descripcion)) {
            $mensjError[] = 5;
        }  
        
        if(empty($modalidad)) {
            $mensjError[] = 6;
        }          
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('numero compra');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('fecha');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('empresa');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('pais empresa');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorVacio('descripcion');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorVacio('modalidad');
                    break;                
            }
        }else {
            
            $precio_total   = $this->obterPrecioTotal();
            $cantidad_armas = $this->obtenerCantidadArmasTotales();
            
            $retorno[] = 1;
            
            $retorno[] = $this->alta_compras_model->altaCompra($nro_compra, $fecha, $empresa, $pais_empresa, $descripcion, $modalidad, $precio_total, $cantidad_armas);
        }
        
        echo json_encode($retorno);
    }
}

?>
