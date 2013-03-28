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
        
        $_SESSION['fichas']     = array(); //inicializo el array de fichas a entregar
        $_SESSION['accesorios'] = array(); //inicializo el array de accesorios a entregar
        
        //cargo las unidades
        $unidades = $this->alta_actas_alta_model->cargoUnidades();
        
        $data['unidades'] = "<option> </option>";
        
        for($i=0; $i < count($unidades); $i=$i+2) {
            $data['unidades'] .= "<option value='".$unidades[$i]."'>".$unidades[$i+1]."</option>";
        }
        //fin cargo unidades
        
        //cargo nro de series de armamentos que esten en deposito inicial
        $nro_series = $this->alta_actas_alta_model->cargoNroSeries();
        
        $aux = '""';
        $data['nro_series'] = "<option onchange='cargoMarcas(".$aux.");'> </option>";
        
        foreach($nro_series as $val) {
            $aux = '"'.$val.'"';
            $data['nro_series'] .= "<option onchange='cargoMarcas(".$aux.");' value='".$val."'>".$val."</option>";
        }
        //fin cargo nro de series de armamento en deposito inicial
        
        //cargo nro de series de armamentos que esten en deposito inicial de accesorios
        $nro_series_accesorios = $this->alta_actas_alta_model->cargoNroSeriesAccesorios();
        
        $aux = '""';
        $data['nro_series_accesorios'] = "<option onchange='cargoMarcasAccesorios(".$aux.");'> </option>";
        
        foreach($nro_series_accesorios as $val) {
            $aux = '"'.$val.'"';
            $data['nro_series_accesorios'] .= "<option onchange='cargoMarcasAccesorios(".$aux.");' value='".$val."'>".$val."</option>";
        }
        //fin cargo nro de series de armamento en deposito inicial        
        
        $this->load->view('alta_actas_alta_view', $data);  
    }
    
    function cargoMarcas() {
        
        $nro_serie     = $_POST['nro_serie'];
        $aux_nro_serie = '"'.$nro_serie.'"';
        
        $marcas = $this->alta_actas_alta_model->cargoMarcas($nro_serie);
        
        $aux = '""';
        $concat = "<option onchange='cargoCalibres(".$aux.",".$aux.");'> </option>";
        
        foreach($marcas as $val) {
            $aux_marca = '"'.$val.'"';
            $concat .= "<option onchange='cargoCalibres(".$aux_nro_serie.",".$aux_marca.");' value='".$val."'>".$val."</option>";
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
        $concat = "<option onchange='cargoModelos(".$aux.",".$aux.",".$aux.");'> </option>";
        
        foreach($calibres as $val) {
            $aux_calibre = '"'.$val.'"';
            $concat .= "<option onchange='cargoModelos(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.");' value='".$val."'>".$val."</option>";
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
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }   
    
    function cargoMarcasAccesorios() {
        
        $nro_serie     = $_POST['nro_serie'];
        $aux_nro_serie = '"'.$nro_serie.'"';
        
        $marcas = $this->alta_actas_alta_model->cargoMarcasAccesorios($nro_serie);
        
        $aux = '""';
        $concat = "<option onchange='cargoCalibresAccesorios(".$aux.",".$aux.");'> </option>";
        
        foreach($marcas as $val) {
            $aux_marca = '"'.$val.'"';
            $concat .= "<option onchange='cargoCalibresAccesorios(".$aux_nro_serie.",".$aux_marca.");' value='".$val."'>".$val."</option>";
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
        $concat = "<option onchange='cargoModelosAccesorios(".$aux.",".$aux.",".$aux.");'> </option>";
        
        foreach($calibres as $val) {
            $aux_calibre = '"'.$val.'"';
            $concat .= "<option onchange='cargoModelosAccesorios(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.");' value='".$val."'>".$val."</option>";
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
        $concat = "<option onchange='cargoNroAccesorios(".$aux.",".$aux.",".$aux.",".$aux.");'> </option>";
        
        foreach($modelos as $val) {
            $aux_modelo = '"'.$val.'"';
            $concat .= "<option onchange='cargoNroAccesorios(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' value='".$val."'>".$val."</option>";
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
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }     
    
    function cargoFichasFiltro() {
        
        $nro_serie = $_SESSION['seleccion_busqueda'];
        $marca     = $_SESSION['seleccion_busqueda1'];
        $calibre   = $_SESSION['seleccion_busqueda2'];
        $modelo    = $_SESSION['seleccion_busqueda3'];
       
        $aux = '""';
        
        $aux_nro_serie = '"'.$nro_serie.'"';
        $nro_series  = "<option onchange='cargoMarcas(".$aux.");'> </option>";
        $nro_series .= "<option selected='selected' onchange='cargoMarcas(".$aux_nro_serie.");' value='".$nro_serie."'>".$nro_serie."</option>";

        $aux_marca = '"'.$marca.'"';
        $marcas  = "<option onchange='cargoCalibres(".$aux.");'> </option>";
        $marcas .= "<option selected='selected' onchange='cargoCalibres(".$aux_nro_serie.",".$aux_marca.");' value='".$marca."'>".$marca."</option>";
        
        $aux_calibre = '"'.$calibre.'"';
        $calibres  = "<option onchange='cargoModelos(".$aux.");'> </option>";
        $calibres .= "<option selected='selected' onchange='cargoModelos(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.");' value='".$calibre."'>".$calibre."</option>";
        
        $modelos  = "<option> </option>";
        $modelos .= "<option selected='selected' value='".$modelo."'>".$modelo."</option>";        
        
        //retorno los datos
        $retorno = array();
        $retorno[] = $nro_series;
        $retorno[] = $marcas;
        $retorno[] = $calibres;
        $retorno[] = $modelos;
        
        echo json_encode($retorno);        
    }
    
    function cargoAccesoriosFiltro() {
        
        $nro_serie     = $_SESSION['seleccion_busqueda'];
        $marca         = $_SESSION['seleccion_busqueda1'];
        $calibre       = $_SESSION['seleccion_busqueda2'];
        $modelo        = $_SESSION['seleccion_busqueda3'];
        $nro_accesorio = $_SESSION['seleccion_busqueda4'];
       
        $aux = '""';
        
        $aux_nro_serie = '"'.$nro_serie.'"';
        $nro_series  = "<option onchange='cargoMarcasAccerios(".$aux.");'> </option>";
        $nro_series .= "<option selected='selected' onchange='cargoMarcasAccerios(".$aux_nro_serie.");' value='".$nro_serie."'>".$nro_serie."</option>";

        $aux_marca = '"'.$marca.'"';
        $marcas  = "<option onchange='cargoCalibresAccesorios(".$aux.",".$aux.");'> </option>";
        $marcas .= "<option selected='selected' onchange='cargoCalibres(".$aux_nro_serie.",".$aux_marca.");' value='".$marca."'>".$marca."</option>";
        
        $aux_calibre = '"'.$calibre.'"';
        $calibres  = "<option onchange='cargoModelosAccesorios(".$aux.",".$aux.",".$aux.");'> </option>";
        $calibres .= "<option selected='selected' onchange='cargoModelosAccesorios(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.");' value='".$calibre."'>".$calibre."</option>";

        $aux_modelo = '"'.$modelo.'"';
        $modelos  = "<option onchange='cargoNroAccesorios(".$aux.",".$aux.",".$aux.",".$aux.");'> </option>";
        $modelos .= "<option selected='selected' onchange='cargoNroAccesorios(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.", ".$aux_modelo.");' value='".$modelo."'>".$modelo."</option>";
        
        $nro_accesorios  = "<option> </option>";
        $nro_accesorios .= "<option selected='selected' value='".$nro_accesorio."'>".$nro_accesorio."</option>";        
        
        //retorno los datos
        $retorno = array();
        $retorno[] = $nro_series;
        $retorno[] = $marcas;
        $retorno[] = $calibres;
        $retorno[] = $modelos;
        $retorno[] = $nro_accesorios;
        
        echo json_encode($retorno);        
    }    
    
    function agregarFicha() {
        
        $nro_serie  = $_POST['nro_serie'];
        $marca      = $_POST['marca'];
        $calibre    = $_POST['calibre'];
        $modelo     = $_POST['modelo'];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($nro_serie)) {
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
            
        //verifico que esa ficha exista en la base de datos en deposito inicial
        if(!$this->alta_actas_alta_model->existeFicha($nro_serie, $marca, $calibre, $modelo)) {
            $mensjError[] = 5;
        }
        
        //verifico que el nro de catalogo no exista ya en el listado
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['fichas']) && !$encontre) {
            
            if($_SESSION['fichas'][$i] == $nro_serie && $_SESSION['fichas'][$i+1] == $marca
                    && $_SESSION['fichas'][$i+2] == $calibre && $_SESSION['fichas'][$i+3] == $modelo) {
                
                $encontre = true;
            }
            
            $i=$i+4; 
        }
        
        if($encontre) {
           $mensjError[] = 6; 
        }
        
        if(count($mensjError) > 0) {
            
            switch ($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('nro serie');
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
                    $retorno[] = $this->mensajes->errorExiste('ficha');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorFichaExiste();
                    break;
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['fichas'][] = $nro_serie;
            $_SESSION['fichas'][] = $marca;
            $_SESSION['fichas'][] = $calibre;
            $_SESSION['fichas'][] = $modelo;
            
            $aux_nro_serie = '"'.$nro_serie.'"';
            $aux_marca     = '"'.$marca.'"';
            $aux_calibre   = '"'.$calibre.'"';
            $aux_modelo    = '"'.$modelo.'"';
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td><img style='cursor: pointer;' onclick='anularFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";
            
            $retorno[] = $concat;
        }
        
        echo json_encode($retorno);        
    }
    
    function anularFicha() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $retorno = array();
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['fichas']) && !$encontre) {
            
            if($_SESSION['fichas'][$i] == $nro_serie && $_SESSION['fichas'][$i+1] == $marca
                    && $_SESSION['fichas'][$i+2] == $calibre && $_SESSION['fichas'][$i+3] == $modelo) {
                
                $encontre = true;
                unset($_SESSION['fichas'][$i]);   //nro_serie
                unset($_SESSION['fichas'][$i+1]); //marca
                unset($_SESSION['fichas'][$i+2]); //calibre
                unset($_SESSION['fichas'][$i+3]); //modelo
                $_SESSION['fichas'] = array_values($_SESSION['fichas']); //reordeno el array
            }
            $i=$i+4;
        }
        
        if($encontre) {
            
            $concat = '';
            
            for($i=0; $i < count($_SESSION['fichas']); $i=$i+4) {

                $aux_nro_serie = '"'.$_SESSION['fichas'][$i].'"';
                $aux_marca     = '"'.$_SESSION['fichas'][$i+1].'"';
                $aux_calibre   = '"'.$_SESSION['fichas'][$i+2].'"';
                $aux_modelo    = '"'.$_SESSION['fichas'][$i+3].'"';

                $concat .= "<tr> 
                                <td style='text-align: center;'>".$_SESSION['fichas'][$i]."</td> <td>".$_SESSION['fichas'][$i+1]."</td> <td>".$_SESSION['fichas'][$i+2]."</td> <td>".$_SESSION['fichas'][$i+3]."</td> <td><img style='cursor: pointer;' onclick='anularFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' src='".  base_url()."images/delete.gif'/></td>
                           </tr>";
            }
            if(count($_SESSION['fichas']) == 0) {
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
    
    function agregarAccesorio() {
        
        $nro_serie     = $_POST['nro_serie'];
        $marca         = $_POST['marca'];
        $calibre       = $_POST['calibre'];
        $modelo        = $_POST['modelo'];
        $nro_accesorio = $_POST['nro_accesorio'];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($nro_serie)) {
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

        if(empty($nro_accesorio)) {
            $mensjError[] = 5;
        }          
        
        //verifico que esa ficha exista en la base de datos en deposito inicial
        if(!$this->alta_actas_alta_model->existeAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio)) {
            $mensjError[] = 6;
        }
        
        //verifico que el nro de catalogo no exista ya en el listado
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['accesorios']) && !$encontre) {
            
            if($_SESSION['accesorios'][$i] == $nro_serie && $_SESSION['accesorios'][$i+1] == $marca
                    && $_SESSION['accesorios'][$i+2] == $calibre && $_SESSION['accesorios'][$i+3] == $modelo && $_SESSION['accesorios'][$i+4] == $nro_accesorio) {
                
                $encontre = true;
            }
            
            $i=$i+5; 
        }
        
        if($encontre) {
           $mensjError[] = 7; 
        }
        
        if(count($mensjError) > 0) {
            
            switch ($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('nro serie');
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
                    $retorno[] = $this->mensajes->errorVacio('nro accesorio');
                    break;                
                
                case 6:
                    $retorno[] = $this->mensajes->errorExiste('accesorio');
                    break;
                
                case 7:
                    $retorno[] = $this->mensajes->errorAccesorioExiste();
                    break;
            }
        }else {
            
            $retorno[] = 1; 
            
            $_SESSION['accesorios'][] = $nro_serie;
            $_SESSION['accesorios'][] = $marca;
            $_SESSION['accesorios'][] = $calibre;
            $_SESSION['accesorios'][] = $modelo;
            $_SESSION['accesorios'][] = $nro_accesorio;
            
            $aux_nro_serie     = '"'.$nro_serie.'"';
            $aux_marca         = '"'.$marca.'"';
            $aux_calibre       = '"'.$calibre.'"';
            $aux_modelo        = '"'.$modelo.'"';
            $aux_nro_accesorio = '"'.$nro_accesorio.'"';
            
            $concat = "<tr> 
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td>".$nro_accesorio."</td> <td><img style='cursor: pointer;' onclick='anularAccesorio(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.",".$aux_nro_accesorio.");' src='".  base_url()."images/delete.gif'/></td>
                       </tr>";
            
            $retorno[] = $concat;
        }
        
        echo json_encode($retorno);        
    }
    
    function anularAccesorio() {
        
        $nro_serie     = $_POST['nro_serie'];
        $marca         = $_POST['marca'];
        $calibre       = $_POST['calibre'];
        $modelo        = $_POST['modelo'];
        $nro_accesorio = $_POST['nro_accesorio'];
        
        $retorno = array();
        $encontre = false;
        $i = 0;
        
        while($i < count($_SESSION['accesorios']) && !$encontre) {
            
            if($_SESSION['accesorios'][$i] == $nro_serie && $_SESSION['accesorios'][$i+1] == $marca
                    && $_SESSION['accesorios'][$i+2] == $calibre && $_SESSION['accesorios'][$i+3] == $modelo && $_SESSION['accesorios'][$i+4] == $nro_accesorio) {
                
                $encontre = true;
                unset($_SESSION['accesorios'][$i]);   //nro_serie
                unset($_SESSION['accesorios'][$i+1]); //marca
                unset($_SESSION['accesorios'][$i+2]); //calibre
                unset($_SESSION['accesorios'][$i+3]); //modelo
                unset($_SESSION['accesorios'][$i+4]); //nro_accesorio
                $_SESSION['accesorios'] = array_values($_SESSION['accesorios']); //reordeno el array
            }
            $i=$i+5;
        }
        
        if($encontre) {
            
            $concat = '';
            
            for($i=0; $i < count($_SESSION['accesorios']); $i=$i+5) {

                $aux_nro_serie     = '"'.$_SESSION['accesorios'][$i].'"';
                $aux_marca         = '"'.$_SESSION['accesorios'][$i+1].'"';
                $aux_calibre       = '"'.$_SESSION['accesorios'][$i+2].'"';
                $aux_modelo        = '"'.$_SESSION['accesorios'][$i+3].'"';
                $aux_nro_accesorio = '"'.$_SESSION['accesorios'][$i+4].'"';

                $concat .= "<tr> 
                                <td style='text-align: center;'>".$_SESSION['accesorios'][$i]."</td> <td>".$_SESSION['accesorios'][$i+1]."</td> <td>".$_SESSION['accesorios'][$i+2]."</td> <td>".$_SESSION['accesorios'][$i+3]."</td> <td>".$_SESSION['accesorios'][$i+4]."</td> <td><img style='cursor: pointer;' onclick='anularAccesorio(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.",".$aux_nro_accesorio.");' src='".  base_url()."images/delete.gif'/></td>
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
    
    function validarDatos() {
        
        $fecha                = $_POST["fecha"];
        $unidad_recibe        = $_POST["unidad_recibe"];
        $representante_sma    = $_POST["representante_sma"];
        $representante_unidad = $_POST["representante_unidad"];
        $supervision          = $_POST["supervision"];
        $observaciones        = $_POST["observaciones"];
        
        $mensjError = array();
        $retorno = array();
        
        if(empty($fecha)) {
            $mensjError[] = 1;
        }
        
        if(empty($unidad_recibe)) {
            $mensjError[] = 2;
        }
        
        if(empty($representante_sma)) {
            $mensjError[] = 3;
        }
        
        if(empty($representante_unidad)) {
            $mensjError[] = 4;
        }        
        
        if(empty($supervision)) {
            $mensjError[] = 5;
        }            
        
        //verifico si hay armamento para entregar
        if(count($_SESSION['fichas']) == 0) {
            $mensjError[] = 6;
        }
        
        if(count($mensjError) > 0) {
            
            switch($mensjError[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('unidad recibe');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('representante sma');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('representante unidad');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorVacio('supervision');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorVacio('fichas');
                    break;               
            }
        }else {
            $nro_acta = $this->alta_actas_alta_model->altaActa_db($fecha, $unidad_recibe, $representante_sma, $representante_unidad, $supervision, $observaciones);
            $retorno[] = 1;
            $retorno[] = $nro_acta;
        }
        
        echo json_encode($retorno);
    }
}

?>