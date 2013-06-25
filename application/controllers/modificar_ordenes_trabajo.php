<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - modificar_ordenes_trabajo
*/

class modificar_ordenes_trabajo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('modificar_ordenes_trabajo_model');
        $this->load->library('mensajes');
        $this->load->library('perms'); 
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()) {
            die($this->mensajes->sinPermisos());
        }         
        
        //Modulo solo visible para el peril 6 y 7 - Usuario Taller de armamento y Administrador Taller de armamento
        if(!$this->perms->verificoPerfil6() && !$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }
    }
    
    function index() {
        
        $nro_orden = $_SESSION['nro_orden'];
        
        //cargo datos de la orden de trabajo
        $datos_orden = $this->modificar_ordenes_trabajo_model->traigoDatos($nro_orden);
        
        /*
            $datos[] = $row->fecha;           0
            $datos[] = $row->nro_serie;       1
            $datos[] = $row->marca;           2
            $datos[] = $row->calibre;         3
            $datos[] = $row->modelo;          4
            $datos[] = $row->observaciones;   5
            $datos[] = $row->idunidad;        6
         */
        
        $data['fecha']         = $datos_orden[0];
        $nro_serie             = $datos_orden[1];
        $marca                 = $datos_orden[2];
        $calibre               = $datos_orden[3];
        $modelo                = $datos_orden[4];
        $data['observaciones'] = $datos_orden[5];
        $idunidad              = $datos_orden[6];
        
        $data['nro_serie'] = $nro_serie;
        $data['marca']     = $marca;
        $data['calibre']   = $calibre;
        $data['modelo']    = $modelo;
        
        //Cargo las unidades
        $unidades = $this->modificar_ordenes_trabajo_model->cargoUnidades();
        
        $data['unidades'] = "<option> </option>";
        
        for($i=0; $i < count($unidades); $i=$i+2) {
            if($unidades[$i] == $idunidad) {
                $data['unidades'] .= "<option selected='selected' value='".$unidades[$i]."'>".$unidades[$i+1]."</option>";
            }else{
                $data['unidades'] .= "<option value='".$unidades[$i]."'>".$unidades[$i+1]."</option>";
            }
        }
        //Fin cargo unidades
        
        $datos = $this->modificar_ordenes_trabajo_model->cargoDatos($nro_serie, $marca, $calibre, $modelo);
        
        $data['tipo_arma'] = $datos[0];
        $data['sistema']   = $datos[1];
        
        $this->load->view('modificar_ordenes_trabajo_view', $data); 
    }
    
    function cargoDatos() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $datos = $this->modificar_ordenes_trabajo_model->cargoDatos($nro_serie, $marca, $calibre, $modelo);
        
        /*
         * $datos[0] - tipo_arma 1 
         * $datos[1] - sistema   2
         */
        
        for($i=0; $i<count($datos); $i=$i+2) {
            $tipo_sistema[] = $datos[$i];
            $tipo_sistema[] = $datos[$i+1];
        }
        
        echo json_encode($tipo_sistema);
    }
    
    function cargoMarcas() {
        
        $nro_serie = $_POST['nro_serie'];
       
        $marcas = $this->modificar_ordenes_trabajo_model->cargoMarcas($nro_serie);
       
        $concat = "<option> </option>";
        
        foreach($marcas as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
    function cargoCalibres() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        
        $calibres = $this->modificar_ordenes_trabajo_model->cargoCalibres($nro_serie, $marca);
        
        $concat = "<option> </option>";
        
        foreach($calibres as $val) {
            $concat .= "<option value='".$val."'>".$val."</option>";
        }
        
        echo $concat;
    }
    
     function cargoModelos() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        
        $modelos = $this->modificar_ordenes_trabajo_model->cargoModelos($nro_serie, $marca, $calibre);
        
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
        
        //Retorno los datos
        $retorno = array();
        $retorno[] = $nro_serie;
        $retorno[] = $marca;
        $retorno[] = $calibre;
        $retorno[] = $modelo;
        
        $datos = $this->modificar_ordenes_trabajo_model->cargoDatos($nro_serie, $marca, $calibre, $modelo);
        
        $retorno[] = $datos[0]; //tipo_arma
        $retorno[] = $datos[1]; //sistema
        
        echo json_encode($retorno);        
    }    
    
    function verHistorico() {
        
        $nro_serie = $_POST['nro_serie'];
        $marca     = $_POST['marca'];
        $calibre   = $_POST['calibre'];
        $modelo    = $_POST['modelo'];
        
        $datos = array();
        
        if(!empty($nro_serie) && !empty($marca) && !empty($calibre) && !empty($modelo) && $this->alta_ordenes_trabajo_model->hayHistorio($nro_serie, $marca, $calibre, $modelo)) {
        
            $datos = $this->modificar_ordenes_trabajo_model->verHistorio($nro_serie, $marca, $calibre, $modelo);

            /*
               $datos[] = $row->nro_acta;
               $datos[] = $row->fecha_transaccion;
               $datos[] = $row->nombreunidad;
             */

            $concat = "<p style='font-weight: bold;'> Detalle de los movimientos previos del armamento seleccionado </p>";

            $concat .= "<div class='datagrid'><table><thead><th> Nro acta </th><th> Fecha </th> <th> Unidad </th></thead>";  

            $j = 0;

            for($i=0; $i<count($datos); $i=$i+3) {
                if($j % 2 == 0){
                    $class = "";
                }else{
                    $class = "alt";
                } 
                $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$datos[$i]."</td> <td>".$datos[$i+1]."</td> <td>".$datos[$i+2]."</td> </tr></tbody>";
                $j++;
            }

            $concat .= "</table>";

            $concat .= "</div>";
        }else {
            $concat = "<p style='font-weight: bold;'> Detalle de los movimientos previos del armamento seleccionado </p>";
            $concat .= "<p style='font-weight: bold;'> VERIFIQUE: No selecciono ningun armamento </p>";
        }
        
        echo $concat;
    }
    
    function validarDatos() {
        
        $patterns = array();
        
        $patterns[] = '/"/';
        $patterns[] = "/'/";        

        $patterns[] = '/{/';
        
        $patterns[] = '/}/';
        
        $patterns[] = '/|/'; 
        
        $fecha         = $_POST["fecha"];
        $unidad        = $_POST["unidad"];
        $nro_serie     = $_POST["nro_serie"];
        $marca         = $_POST["marca"];
        $calibre       = $_POST["calibre"];
        $modelo        = $_POST["modelo"];
        
        $observaciones = preg_replace($patterns, '', $_POST["observaciones"]);
        
        $mensja_error = array();
        $retorno = array();
        
        if(empty($fecha)) {
            $mensja_error[] = 1;
        }
        
        if(empty($unidad)) {
            $mensja_error[] = 2;
        }
        
        if(empty($nro_serie)) {
            $mensja_error[] = 3;
        }
        
        if(empty($marca)) {
            $mensja_error[] = 4;
        }        
        
        if(empty($calibre)) {
            $mensja_error[] = 5;
        }        

        if(empty($modelo)) {
            $mensja_error[] = 6;
        }        

        if(empty($observaciones)) {
            $mensja_error[] = 7;
        }  
        
        if($this->modificar_ordenes_trabajo_model->verificoOrdenTrabajo($nro_serie, $marca, $calibre, $modelo)) {
            $mensja_error[] = 8;
        }        
       
        if(count($mensja_error) > 0) {
            
            switch($mensja_error[0]) {
                
                case 1:
                    $retorno[] = $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    $retorno[] = $this->mensajes->errorVacio('unidad');
                    break;
                
                case 3:
                    $retorno[] = $this->mensajes->errorVacio('nro serie');
                    break;
                
                case 4:
                    $retorno[] = $this->mensajes->errorVacio('marca');
                    break;
                
                case 5:
                    $retorno[] = $this->mensajes->errorVacio('calibre');
                    break;
                
                case 6:
                    $retorno[] = $this->mensajes->errorVacio('modelo');
                    break;
                
                case 7:
                    $retorno[] = $this->mensajes->errorVacio('observaciones');
                    break;
                
                case 8:
                    $retorno[] = "ERROR: El armamento seleccionado ya tiene una orden de trabajo abierta";
                    break;                
            }
        }else {
            $nro_orden = $_SESSION['nro_orden'];
            $this->modificar_ordenes_trabajo_model->modificarOrdenTrabajo($fecha, $unidad, $nro_serie, $marca, $calibre, $modelo, $observaciones, $nro_orden);
            $retorno[] = 1;
            unset($_SESSION['nro_orden']);
        }
        
        echo json_encode($retorno);
    }
}

?>