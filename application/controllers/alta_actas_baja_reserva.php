<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Tercera Iteracion
* Clase - alta_actas_baja_reserva
*/

class alta_actas_baja_reserva extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('alta_actas_baja_reserva_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 8 - Usuario Reserva
        if(!$this->perms->verificoPerfil8()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $_SESSION['fichas']     = array(); //inicializo el array de fichas a entregar
        $_SESSION['accesorios'] = array(); //inicializo el array de accesorios a entregar
        
        //Cargo las unidades
        $unidades = $this->alta_actas_baja_reserva_model->cargoUnidades();
        
        $data['unidades'] = "<option> </option>";
        
        for($i=0; $i < count($unidades); $i=$i+2) {
            $data['unidades'] .= "<option value='".$unidades[$i]."'>".$unidades[$i+1]."</option>";
        }
        //Fin cargo unidades
        
        $this->load->view('alta_actas_baja_reserva_view', $data);  
    }
    
    function cargoNroSeries() {

        $unidad = $_POST['unidad'];
        
        $retorno = array();
        
       //Cargo nro de series de armamentos que esten en la unidad
        $nro_series = $this->alta_actas_baja_reserva_model->cargoNroSeries($unidad);
        
        $retorno[0] = "<option> </option>";
        
        foreach($nro_series as $val) {
            $retorno[0] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo nro de series de armamento que este en esa unidad
        
        //Cargo nro de series de accesorios que esten en la unidad
        $nro_series_accesorios = $this->alta_actas_baja_reserva_model->cargoNroSeriesAccesorios($unidad);
        
        $retorno[1] = "<option> </option>";
        
        foreach($nro_series_accesorios as $val) {
            $retorno[1] .= "<option value='".$val."'>".$val."</option>";
        }
        //Fin cargo nro de series de accesorios en unidad
        
        echo json_encode($retorno);
    }
    
    function cargoMarcas() {
        
        $unidad     = $_POST['unidad'];
        $nro_serie  = $_POST['nro_serie'];
        
        $marcas = $this->alta_actas_baja_reserva_model->cargoMarcas($unidad, $nro_serie);
        
        $concat = "<option> </option>";
        
        foreach($marcas as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibres() {
       
        $unidad    = $_POST['unidad'];
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        
        $calibres = $this->alta_actas_baja_reserva_model->cargoCalibres($unidad, $nro_serie, $marca);
        
        $concat = "<option> </option>";
        
        foreach($calibres as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
     function cargoModelos() {
         
        $unidad    = $_POST['unidad'];
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        
        $modelos = $this->alta_actas_baja_reserva_model->cargoModelos($unidad, $nro_serie, $marca, $calibre);
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }   
    
    function cargoMarcasAccesorios() {
        
        $unidad     = $_POST['unidad'];
        $nro_serie  = $_POST['nro_serie'];
      
        $marcas = $this->alta_actas_baja_reserva_model->cargoMarcasAccesorios($unidad, $nro_serie);
        
        $concat = "<option> </option>";
        
        foreach($marcas as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibresAccesorios() {
        
        $unidad    = $_POST['unidad'];
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        
        $calibres = $this->alta_actas_baja_reserva_model->cargoCalibresAccesorios($unidad, $nro_serie, $marca);
        
        $concat = "<option> </option>";
        
        foreach($calibres as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
     function cargoModelosAccesorios() {
        
        $unidad    = $_POST['unidad'];
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        
        $modelos = $this->alta_actas_baja_reserva_model->cargoModelosAccesorios($unidad, $nro_serie, $marca, $calibre);
    
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    } 
    
     function cargoNroAccesorios() {
        
        $unidad    = $_POST['unidad'];
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $modelos = $this->alta_actas_baja_reserva_model->cargoNroAccesorios($unidad, $nro_serie, $marca, $calibre, $modelo);
        
        $concat = "<option> </option>";
        
        foreach($modelos as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }     
    
    function cargoFichasFiltro() {
    
        unset($_SESSION['unidad']);
        
        $nro_serie = $_SESSION['seleccion_busqueda'];
        $marca     = $_SESSION['seleccion_busqueda1'];
        $calibre   = $_SESSION['seleccion_busqueda2'];
        $modelo    = $_SESSION['seleccion_busqueda3'];
       
        //$unidad = $_POST['unidad'];
        
        //Retorno los datos
        $retorno = array();
        $retorno[] = $nro_serie;
        $retorno[] = $marca;
        $retorno[] = $calibre;
        $retorno[] = $modelo;
        
        echo json_encode($retorno);        
    }
    
    function cargoAccesoriosFiltro() {
        
        unset($_SESSION['unidad']);
        
        $nro_serie     = $_SESSION['seleccion_busqueda'];
        $marca         = $_SESSION['seleccion_busqueda1'];
        $calibre       = $_SESSION['seleccion_busqueda2'];
        $modelo        = $_SESSION['seleccion_busqueda3'];
        $nro_accesorio = $_SESSION['seleccion_busqueda4'];
      
        //$unidad = $_POST['unidad'];
        
        //Retorno los datos
        $retorno = array();
        $retorno[] = $nro_serie;
        $retorno[] = $marca;
        $retorno[] = $calibre;
        $retorno[] = $modelo;
        $retorno[] = $nro_accesorio;
        
        echo json_encode($retorno);        
    }    
    
    function agregarFicha() {
        
        $unidad     = $_POST['unidad'];
        $nro_serie  = $_POST['nro_serie'];
        $marca      = $_POST['marca'];
        $calibre    = $_POST['calibre'];
        $modelo     = $_POST['modelo'];
        
        $mensaje_error = array();
        $retorno = array();
        
        if(empty($nro_serie)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($marca)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($calibre)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($modelo)) {
            $mensaje_error[] = 4;
        }
            
        if(empty($unidad)) {
            $mensaje_error[] = 5;
        }         
        
        //Verifico que esa ficha exista en la base de datos en deposito inicial
        if(!$this->alta_actas_baja_reserva_model->existeFicha($unidad, $nro_serie, $marca, $calibre, $modelo)) {
            $mensaje_error[] = 6;
        }
        
        //Verifico que el nro de catalogo no exista ya en el listado
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
           $mensaje_error[] = 7; 
        }
        
        if(count($mensaje_error) > 0) {
            
            switch ($mensaje_error[0]) {
                
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
                    $retorno[] = $this->mensajes->errorVacio('unidad');
                    break;                
                
                case 6:
                    $retorno[] = $this->mensajes->errorExiste('ficha');
                    break;
                
                case 7:
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
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td style='text-align: center;'><img style='cursor: pointer;' onclick='anularFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' src='".  base_url()."images/delete.gif'/></td>
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
                                <td style='text-align: center;'>".$_SESSION['fichas'][$i]."</td> <td>".$_SESSION['fichas'][$i+1]."</td> <td>".$_SESSION['fichas'][$i+2]."</td> <td>".$_SESSION['fichas'][$i+3]."</td> <td style='text-align: center;'><img style='cursor: pointer;' onclick='anularFicha(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.");' src='".  base_url()."images/delete.gif'/></td>
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
        
        $unidad        = $_POST['unidad'];
        $nro_serie     = $_POST['nro_serie'];
        $marca         = $_POST['marca'];
        $calibre       = $_POST['calibre'];
        $modelo        = $_POST['modelo'];
        $nro_accesorio = $_POST['nro_accesorio'];
        
        $mensaje_error = array();
        $retorno = array();
        
        if(empty($nro_serie)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($marca)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($calibre)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($modelo)) {
            $mensaje_error[] = 4;
        }

        if(empty($nro_accesorio)) {
            $mensaje_error[] = 5;
        }    
        
        if(empty($unidad)) {
            $mensaje_error[] = 6;
        }           
        
        //Verifico que esa ficha exista en la base de datos en deposito inicial
        if(!$this->alta_actas_baja_reserva_model->existeAccesorio($unidad, $nro_serie, $marca, $calibre, $modelo, $nro_accesorio)) {
            $mensaje_error[] = 7;
        }
        
        //Verifico que el nro de catalogo no exista ya en el listado
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
           $mensaje_error[] = 8; 
        }
        
        if(count($mensaje_error) > 0) {
            
            switch ($mensaje_error[0]) {
                
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
                    $retorno[] = $this->mensajes->errorVacio('unidad');
                    break;                 
                
                case 7:
                    $retorno[] = $this->mensajes->errorExiste('accesorio');
                    break;
                
                case 8:
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
                            <td style='text-align: center;'>".$nro_serie."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td>".$nro_accesorio."</td> <td style='text-align: center;'><img style='cursor: pointer;' onclick='anularAccesorio(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.",".$aux_nro_accesorio.");' src='".  base_url()."images/delete.gif'/></td>
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
                                <td style='text-align: center;'>".$_SESSION['accesorios'][$i]."</td> <td>".$_SESSION['accesorios'][$i+1]."</td> <td>".$_SESSION['accesorios'][$i+2]."</td> <td>".$_SESSION['accesorios'][$i+3]."</td> <td>".$_SESSION['accesorios'][$i+4]."</td> <td style='text-align: center;'><img style='cursor: pointer;' onclick='anularAccesorio(".$aux_nro_serie.",".$aux_marca.",".$aux_calibre.",".$aux_modelo.",".$aux_nro_accesorio.");' src='".  base_url()."images/delete.gif'/></td>
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
    
    function seteoUnidad() {
        $_SESSION['unidad'] = $_POST['unidad'];
    }
    
    function validarDatos() {
        
        $patterns = array();
        $patterns[] = '/"/';
        $patterns[] = "/'/";
        
        $fecha                = $_POST["fecha"];
        $unidad_entrega       = $_POST["unidad_entrega"];
        $representante_sma    = preg_replace($patterns, '', $_POST["representante_sma"]);
        $representante_unidad = preg_replace($patterns, '', $_POST["representante_unidad"]);
        $supervision          = preg_replace($patterns, '', $_POST["supervision"]);
        $observaciones        = $_POST["observaciones"];
        
        $mensaje_error = array();
        $retorno = array();
        
        if(empty($fecha)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($unidad_entrega)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($representante_sma)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($representante_unidad)) {
            $mensaje_error[] = 4;
        }        
        
        if(empty($supervision)) {
            $mensaje_error[] = 5;
        }            
        
        //Verifico si hay armamento para entregar
        if(count($_SESSION['fichas']) == 0) {
            $mensaje_error[] = 6;
        }
        
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('unidad entrega');
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
            $nro_acta = $this->alta_actas_baja_reserva_model->altaActa_db($fecha, $unidad_entrega, $representante_sma, $representante_unidad, $supervision, $observaciones);
            $retorno[] = 1;
            $retorno[] = $nro_acta;
        }
        
        echo json_encode($retorno);
    }
}

?>
