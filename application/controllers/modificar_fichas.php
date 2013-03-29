<?php

class modificar_fichas extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_fichas_model');
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
        
        if(isset($_SESSION['datos_ficha']) && count($_SESSION['datos_ficha'])>0) {
            $datos_ficha = $_SESSION['datos_ficha'];
        }else {
            $datos_ficha = array();
            $datos_ficha[] = "";
            $datos_ficha[] = "";
            $datos_ficha[] = "";
            $datos_ficha[] = "";
        }        
        
        //Inicializo variable de session de catalogos vacia
        if(isset($_SESSION['accesorios'])) {
            unset($_SESSION['accesorios']);
        }
       
        $_SESSION['accesorios'] = array();        

        if(isset($_SESSION['piezas'])) {
            unset($_SESSION['piezas']);
        }
       
        $_SESSION['piezas'] = array();        
        
        /*
         * $datos_ficha[0] => nro_serie 
         * $datos_ficha[1] => marca 
         * $datos_ficha[2] => calibre 
         * $datos_ficha[3] => modelo 
         */
        
        $data['nro_serie'] = $datos_ficha[0];
        $data['marca']     = $datos_ficha[1];
        $data['calibre']   = $datos_ficha[2];
        $data['modelo']    = $datos_ficha[3];
        
        $compra_catalogo = $this->modificar_fichas_model->cargoCompraCatalogo($datos_ficha[0], $datos_ficha[1], $datos_ficha[2], $datos_ficha[3]);
        
        $data['nro_compra']   = $compra_catalogo[0];
        $data['nro_catalogo'] = $compra_catalogo[1]; 
        
        //accesorios
        $accesorios = $this->modificar_fichas_model->cargoAccesorios();
        
        $data['tipo_accesorios'] = "<option> </option>";
        
        foreach($accesorios as $val) {
            $data['tipo_accesorios'] .= "<option value='".$val."'>".$val."</option>";
        }        
        
        //piezas
        $piezas = $this->modificar_fichas_model->cargoPiezas();
        
        $data['tipo_piezas'] = "<option> </option>";
        
        foreach($piezas as $val) {
            $data['tipo_piezas'] .= "<option value='".$val."'>".$val."</option>";
        }        
        
        //cargo todos los accesorios de esa ficha
        
        if($this->modificar_fichas_model->existeAccesoriosFicha($datos_ficha[0], $datos_ficha[1], $datos_ficha[2], $datos_ficha[3])) {
            
            $datos_accesorios = $this->modificar_fichas_model->cargoAccesoriosFicha($datos_ficha[0], $datos_ficha[1], $datos_ficha[2], $datos_ficha[3]);
            
            /*
             * $datos_accesorios[] = $row->nro_accesorio;
             * $datos_accesorios[] = $row->tipo_accesorio;
             * $datos_accesorios[] = $row->descripcion;
             */
            
            $concat = "";
            
            for($i=0; $i<count($datos_accesorios); $i=$i+3) {
            
                $nro_accesorio         = $datos_accesorios[$i];
                $tipo_accesorio        = $datos_accesorios[$i+1];
                $descripcion_accesorio = $datos_accesorios[$i+2];
                
                $_SESSION['accesorios'][] = $nro_accesorio;
                $_SESSION['accesorios'][] = $tipo_accesorio;
                $_SESSION['accesorios'][] = $descripcion_accesorio;

                $aux = '"'.$nro_accesorio.'"';
                
                if($this->modificar_fichas_model->existeHistorialAccesorio($datos_ficha[0],$datos_ficha[1],$datos_ficha[2],$datos_ficha[3], $nro_accesorio)) {
                    $borrar = "<td> </td>";
                }else{
                    $borrar = "<td style='text-align: center;'><img style='cursor: pointer;' onclick='anularAccesorio(".$aux.");' src='".  base_url()."images/delete.gif'/></td>";
                }

                $concat .= "<tr> 
                                <td style='text-align: center;'>".$nro_accesorio."</td> <td>".$tipo_accesorio."</td> <td>".$descripcion_accesorio."</td>".$borrar."
                           </tr>";
            }
        }else {
            $concat = "<tr> <td> </td> <td> </td> <td> </td> </tr>";
        }
        
        $data['accesorios'] = $concat;

        //fin cargo accesorios
        
        //cargo todas las piezas de esa ficha
        
        if($this->modificar_fichas_model->existePiezasFicha($datos_ficha[0], $datos_ficha[1], $datos_ficha[2], $datos_ficha[3])) {
            
            $datos_piezas = $this->modificar_fichas_model->cargoPiezasFicha($datos_ficha[0], $datos_ficha[1], $datos_ficha[2], $datos_ficha[3]);
            
            /*
             * $datos_piezas[] = $row->nro_pieza;   0
             * $datos_piezas[] = $row->tipo_pieza;  1
             * $datos_piezas[] = $row->descripcion; 2
             */ 
            
            $concat = "";
            
            for($i=0; $i<count($datos_piezas); $i=$i+3) {
            
                $nro_pieza         = $datos_piezas[$i];
                $tipo_pieza        = $datos_piezas[$i+1];
                $descripcion_pieza = $datos_piezas[$i+2];                

                $_SESSION['piezas'][] = $nro_pieza;
                $_SESSION['piezas'][] = $tipo_pieza;
                $_SESSION['piezas'][] = $descripcion_pieza;

                $aux = '"'.$nro_pieza.'"';
                
                $borrar = "<td style='text-align: center;'><img style='cursor: pointer;' onclick='anularPieza(".$aux.");' src='".  base_url()."images/delete.gif'/></td>";

                $concat .= "<tr> 
                                <td style='text-align: center;'>".$nro_pieza."</td> <td>".$tipo_pieza."</td> <td>".$descripcion_pieza."</td>".$borrar."
                           </tr>";     
            }
        }else {
            $concat = "<tr> <td> </td> <td> </td> <td> </td> </tr>";
        }
        
        $data['piezas'] = $concat;
        
        //fin cargo piezas
        
        //cargo la vista
        $this->load->view('modificar_fichas_view', $data);  
    }
    
    function cargoComprasFiltro() {
        
        $nro_compra   = $_SESSION['seleccion_busqueda'];
        $nro_catalogo = $_SESSION['seleccion_busqueda1'];
        
        //cargo los numeros de compras filtrados por el seleccionado
        $nro_compras = $this->modificar_fichas_model->cargoNroCompras();
        
        $compras = "<option> </option>";
        
        foreach($nro_compras as $val) {
            
            if($nro_compra == $val) {
                $compras .= "<option onchange='cargoNroCatalogos(".$val.")' selected='selected' value='".$val."'>".$val."</option>";
            }else{
                $compras .= "<option onchange='cargoNroCatalogos(".$val.")' value='".$val."'>".$val."</option>";
            }
        }
        
        //cargo los numeros de catalogos filtrados por el seleccionado
        $nro_catalogos = $this->modificar_fichas_model->cargoNroCatalogos($nro_compra);
        
        $catalogos = "<option> </option>";
        
        foreach($nro_catalogos as $val) {
            if($nro_catalogo == $val) {
                $catalogos .= "<option onchange='cargoInformacion(".$val.")' selected='selected' value='".$val."'>".$val."</option>";
            }else{
                $catalogos .= "<option onchange='cargoInformacion(".$val.")' value='".$val."'>".$val."</option>";
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
        
        $nro_catalogos = $this->modificar_fichas_model->cargoNroCatalogos($nro_compra);
        
        $aux = '""';
        
        $concat = "<option onchange='cargoInformacion(".$aux.")' value=''> </option>";
        
        foreach($nro_catalogos as $val) {
            $concat .= "<option onchange='cargoInformacion(".$val.")' value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoInformacion() {
        
        $nro_catalogo = $_POST['nro_catalogo'];
        $info_catalogo = array();
        
        if($this->modificar_fichas_model->existeNroCatalogo($nro_catalogo)) {
            $info_catalogo = $this->modificar_fichas_model->cargoInformacion($nro_catalogo);
        }
        
        echo json_encode($info_catalogo);
    }
    
    function cargoInformacionArray($nro_catalogo) {
        
        $info_catalogo = array();
        
        if($this->modificar_fichas_model->existeNroCatalogo($nro_catalogo)) {
            $info_catalogo = $this->modificar_fichas_model->cargoInformacion($nro_catalogo);
        }
        
        return $info_catalogo;
    }    
    
    function cargoAccesorios() {
        
        $accesorios = $this->modificar_fichas_model->cargoAccesorios();
        
        $concat = "<option> </option>";
        
        foreach($accesorios as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }  
        
        echo $concat;
    }
    
    function cargoPiezas() {

        $piezas = $this->modificar_fichas_model->cargoPiezas();
        
        $concat = "<option> </option>";
        
        foreach($piezas as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function agregarAccesorio() {
        
        $nro_accesorio         = $_POST['nro_accesorio'];
        $tipo_accesorio        = $_POST['tipo_accesorio'];
        $descripcion_accesorio = $_POST['descripcion_accesorio'];
        $nro_catalogo          = $_POST['nro_catalogo'];
        $nro_serie             = $_POST['nro_serie'];
        
        $info_catalogos = array();
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
            
            if(!is_null($nro_catalogo)) {
                $info_catalogos = $this->cargoInformacionArray($nro_catalogo);
                $marca   = $info_catalogos[0];
                $calibre = $info_catalogos[1];
                $modelo  = $info_catalogos[2];

                if($this->modificar_fichas_model->existeAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio)) {
                    $mensjError[] = 5;
                }  
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
        
        if(!$this->form_validation->numeric($nro_accesorio)) {
            $mensjError[] = 7;
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
                
                case 7:
                    $retorno[] = $this->mensajes->errorNumerico('nro accesorio');
                    break;                
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['accesorios'][] = $nro_accesorio;
            $_SESSION['accesorios'][] = $tipo_accesorio;
            $_SESSION['accesorios'][] = $descripcion_accesorio;
            
            $aux = '"'.$nro_accesorio.'"';
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$nro_accesorio."</td> <td>".$tipo_accesorio."</td> <td>".$descripcion_accesorio."</td> <td style='text-align: center;'><img style='cursor: pointer;' onclick='anularAccesorio(".$aux.");' src='".  base_url()."images/delete.gif'/></td>
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
        
        $info_catalogos = array();
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
           
            if(!is_null($nro_catalogo)) {

                $info_catalogos = $this->cargoInformacionArray($nro_catalogo);
                $marca   = $info_catalogos[0];
                $calibre = $info_catalogos[1];
                $modelo  = $info_catalogos[2];

                if($this->modificar_fichas_model->existePieza($nro_serie, $marca, $calibre, $modelo, $nro_pieza)) {
                    $mensjError[] = 5;
                }  
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
        
        if(!$this->form_validation->numeric($nro_pieza)) {
            $mensjError[] = 7;
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
                    $retorno[] = $this->mensajes->errorExiste('pieza');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorAccesorioExiste();
                    break;
                
                case 7:
                    $retorno[] = $this->mensajes->errorNumerico('nro pieza');
                    break;                
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['piezas'][] = $nro_pieza;
            $_SESSION['piezas'][] = $tipo_pieza;
            $_SESSION['piezas'][] = $descripcion_pieza;
            
            $aux = '"'.$nro_pieza.'"';
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$nro_pieza."</td> <td>".$tipo_pieza."</td> <td>".$descripcion_pieza."</td> <td style='text-align: center;'><img style='cursor: pointer;' onclick='anularPieza(".$aux.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";
            
            $retorno[] = $concat;
        }
        
        echo json_encode($retorno);           
    }
    
    function anularAccesorio() {
        
        $nro_accesorio = $_POST['nro_accesorio'];
        
        $retorno = array();
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['accesorios']) && !$encontre) {
            
            if($_SESSION['accesorios'][$i] == $nro_accesorio) {
                $encontre = true;
                unset($_SESSION['accesorios'][$i]); //nro_accesorio
                unset($_SESSION['accesorios'][$i+1]); //tipo_accesorio
                unset($_SESSION['accesorios'][$i+2]); //descripcion_accesorio
                $_SESSION['accesorios'] = array_values($_SESSION['accesorios']); //reordeno el array
            }
            $i=$i+3;
        }
        
        if($encontre) {
            
            $concat = '';
            
            for($i=0; $i < count($_SESSION['accesorios']); $i=$i+3) {

                $aux = '"'.$_SESSION['accesorios'][$i].'"';

                if($this->modificar_fichas_model->existeHistorialAccesorio($_SESSION['datos_ficha'][0],$_SESSION['datos_ficha'][1],$_SESSION['datos_ficha'][2],$_SESSION['datos_ficha'][3],$_SESSION['accesorios'][$i])) {
                    $borrar = "<td> </td>";
                }else{
                    $borrar = "<td style='text-align: center;'><img style='cursor: pointer;' onclick='anularAccesorio(".$aux.");' src='".  base_url()."images/delete.gif'/></td>";
                }                
                
                $concat .= "<tr> 
                                <td style='text-align: center;'>".$_SESSION['accesorios'][$i]."</td> <td>".$_SESSION['accesorios'][$i+1]."</td> <td>".$_SESSION['accesorios'][$i+2]."</td>".$borrar."
                           </tr>";
            }
            if(count($_SESSION['accesorios']) == 0) {
                $retorno[] = 0;
            }else {
                $retorno[] = 1;
                $retorno[] = $concat;
            }
        }else {
            $retorno[] = 0;
        }
        
        echo json_encode($retorno);        
    }
    
    function anularPieza() {
        
        $nro_pieza = $_POST['nro_pieza'];
        
        $retorno = array();
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['piezas']) && !$encontre) {
            
            if($_SESSION['piezas'][$i] == $nro_pieza) {
                $encontre = true;
                unset($_SESSION['piezas'][$i]); //nro_accesorio
                unset($_SESSION['piezas'][$i+1]); //tipo_accesorio
                unset($_SESSION['piezas'][$i+2]); //descripcion_accesorio
                $_SESSION['piezas'] = array_values($_SESSION['piezas']); //reordeno el array
            }
            $i=$i+3;
        }
        
        if($encontre) {
            
            $concat = '';
            
            for($i=0; $i<count($_SESSION['piezas']); $i=$i+3) {

                $aux = '"'.$_SESSION['piezas'][$i].'"';
                
                $borrar = "<td style='text-align: center;'><img style='cursor: pointer;' onclick='anularPieza(".$aux.");' src='".  base_url()."images/delete.gif'/></td>";

                $concat .= "<tr> 
                                <td style='text-align: center;'>".$_SESSION['piezas'][$i]."</td> <td>".$_SESSION['piezas'][$i+1]."</td> <td>".$_SESSION['piezas'][$i+2]."</td>".$borrar."
                           </tr>";
            }
            if(count($_SESSION['piezas']) == 0) {
                $retorno[] = 0;
            }else {
                $retorno[] = 1;
                $retorno[] = $concat;
            }
        }else {
            $retorno[] = 0;
        }
        
        echo json_encode($retorno);        
    }
    
    function validarDatos() {
        
        $nro_serie = $_SESSION['datos_ficha'][0];
        $marca     = $_SESSION['datos_ficha'][1];
        $calibre   = $_SESSION['datos_ficha'][2];
        $modelo    = $_SESSION['datos_ficha'][3];
        $this->modificar_fichas_model->modificarFicha($nro_serie, $marca, $calibre, $modelo);
        echo 1;
    }
}

?>
