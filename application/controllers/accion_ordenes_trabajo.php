<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - accion_ordenes_trabajo
*/

class accion_ordenes_trabajo extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('accion_ordenes_trabajo_model');
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
        
        $_SESSION['nro_orden']  = "";
        $_SESSION['nro_accion'] = "";
        
        //cargo los nro de ordenes ingresados hasta el momento
        $nro_ordenes = $this->accion_ordenes_trabajo_model->cargoNroOrdenes();
        
        $data['nro_ordenes'] = "<option value=''>Seleccione opcion..</option>";
        
        foreach($nro_ordenes as $val) {
            $data['nro_ordenes'] .= "<option value='".$val."'>".$val."</option>";
        }
        //fin cargo nro de ordenes ingresados hasta el momento
        
        //cargo las secciones ingresadas hasta el momento
        $secciones = $this->accion_ordenes_trabajo_model->cargoSecciones();
        
        $data['secciones'] = "<option value=''>Seleccione opcion..</option>";
        
        foreach($secciones as $val) {
            $data['secciones'] .= "<option value='".$val."'>".$val."</option>";
        }
        //fin cargo las secciones ingresadas hasta el momento
        
        //Llamo a la vista
        $this->load->view('accion_ordenes_trabajo_view', $data);  
    }
    
    function accionPiezasSecundarias() {
        
        $fecha          = $_POST["fecha"];
        $nro_orden      = $_POST["nro_orden"];
        $seccion        = $_POST["seccion"];
        $observaciones  = $_POST["observaciones"];
        
        $mensaje_error = array();
        
        if(empty($fecha)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($nro_orden)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($seccion)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($observaciones)) {
            $mensaje_error[] = 4;
        }        
         
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nro orden');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('seccion');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('observaciones');
                    break;
            }
        }else {
            $tipo_accion = 1; //accion piezas secundarias
            $nro_accion = $this->accion_ordenes_trabajo_model->altaAccionSimple($fecha, $nro_orden, $seccion, $observaciones, $tipo_accion);
            //almacen info de orden en variables de session 
            $_SESSION['nro_orden']  = $nro_orden;
            $_SESSION['nro_accion'] = $nro_accion;       
            echo 1;
        }        
        
    }
    
    function accionPiezasAsociadas() {
        
        $fecha          = $_POST["fecha"];
        $nro_orden      = $_POST["nro_orden"];
        $seccion        = $_POST["seccion"];
        $observaciones  = $_POST["observaciones"];
        
        $mensaje_error = array();
        
        if(empty($fecha)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($nro_orden)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($seccion)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($observaciones)) {
            $mensaje_error[] = 4;
        }        
         
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nro orden');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('seccion');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('observaciones');
                    break;
            }
        }else {
            $tipo_accion = 2; //accion piezas asociadas
            $nro_accion = $this->accion_ordenes_trabajo_model->altaAccionSimple($fecha, $nro_orden, $seccion, $observaciones, $tipo_accion);
            //almacen info de orden en variables de session 
            $_SESSION['nro_orden']  = $nro_orden;
            $_SESSION['nro_accion'] = $nro_accion;       
            echo 1;
        }          
    }    

    function cargoOrdenesTrabajoFiltro() {
        
        $nro_ordenes = $this->accion_ordenes_trabajo_model->cargoNroOrdenes();
        
        $concat = "<option value=''>Seleccione opcion..</option>";
        
        foreach($nro_ordenes as $val) {
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
    
    function cargoDatosArma() {
        
        $nro_orden = $_POST['nro_orden'];
        
        if(!empty($nro_orden)) {
            $datos_arma = $this->accion_ordenes_trabajo_model->cargoDatosArma($nro_orden);
        }else {
            $datos_arma[0] = 0;
        }
        
        echo json_encode($datos_arma);
    }
    
    function cargoSecciones() {
        
        $secciones = $this->accion_ordenes_trabajo_model->cargoSecciones();
        
        $concat = "<option> </option>";
        
        foreach($secciones as $val) {
            if($val == $_SESSION['alta_seccion']) {
                $concat .= "<option selected='selected' value='".$val."'>".$val."</option>";
                $_SESSION['alta_seccion'] = "";
            }else {
                $concat .= "<option value='".$val."'>".$val."</option>";
            }
        }
        
        echo $concat;
    }
    
    function cargoAcciones() {
        
        $nro_orden = $_POST['nro_orden'];
        
        if(!empty($nro_orden)) {
            $datos_acciones = $this->accion_ordenes_trabajo_model->cargoAcciones($nro_orden);
            /*
                $retorno[] = $row->nro_accion;  0
                $retorno[] = $row->fecha;       1 
                $retorno[] = $row->seccion;     2
                $retorno[] = $row->tipo_accion; 3     
            */   
            
            $concat  = "";
            
            for($i=0; $i<count($datos_acciones); $i=$i+4) {
                
                switch($datos_acciones[$i+3]){
                    
                    case 0:
                        $tipo_accion = "accion simple";
                        break;
                    
                    case 1:
                        $tipo_accion = "accion piezas secundarias";
                        break;
                    
                    case 2:
                        $tipo_accion = "accion piezas asociadas";
                        break;
                }
                
                $aux_nro_accion = '"'.$datos_acciones[$i].'"';
                
                $concat .= "<tr> 
                            <td style='text-align: center;'>".$datos_acciones[$i]."</td>
                            <td style='text-align: center;'>".$datos_acciones[$i+1]."</td>
                            <td>".$datos_acciones[$i+2]."</td>  
                            <td>".$tipo_accion."</td> 
                            <td style='text-align: center; cursor: pointer;' onclick='verInformacion(".$aux_nro_accion.");'><img src='".base_url()."images/eye.png' /></td>
                            <td style='text-align: center; cursor: pointer;' onclick='editarAccion(".$aux_nro_accion.");'><img src='".base_url()."images/edit.png' /></td>
                            <td style='text-align: center; cursor: pointer;' onclick='eliminarAccion(".$aux_nro_accion.");'><img src='".base_url()."images/delete.gif' /></td>
                            </tr>";
            }
            
            echo $concat;
            
        }else {
            echo 0;
        }
    }
    
    function editarAccion() {
        $_SESSION['editar_nro_accion'] = $_POST['nro_accion'];
        $nro_accion = $_SESSION['editar_nro_accion'];
        
        $tipo_accion = $this->accion_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
        echo $tipo_accion;
    }
    
    function verInformacion() {
        
        $nro_accion = $_POST['nro_accion'];

        $tipo_accion = $this->accion_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
        switch($tipo_accion) {
            
            case 0: //accion simple
                $concat = $this->verInformacionAccionSimple($nro_accion);
                break;
            
            case 1: //accion piezas secundarias
                $concat = $this->verInformacionAccionSecundaria($nro_accion);
                break;
            
            case 2: //accion piezas asociadas
                $concat = $this->verInformacionAccionAsociada($nro_accion);
                break;
        }
        
        echo $concat;
    }
    
    function verInformacionAccionSimple($nro_accion) {
        
        //obtengo informacion de la accion
        $datos = $this->accion_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
         *  $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */
        
        $concat = "<p style='font-weight: bold;'> Detalle de la accion Nro - ".$nro_accion." Nro de orden - ".$datos[0]." </p>";

        $concat .= "<div class='datagrid'><table><thead><th> Fecha </th><th> Seccion </th></thead>";  
        
        $j = 0;

        for($i=0; $i<count($datos); $i=$i+5) {
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            } 
            $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$datos[$i+1]."</td> <td>".$datos[$i+2]."</td> </tr></tbody>";
            $j++;
        }

        $concat .= "</table>";

        $concat .= "</div>";
        
        $concat .= "<p style='font-weight: bold;'> Detalles </p>";
        
        $concat .= "<div class='datagrid'><table><thead><th> Detalles </th></thead>";  
        
        $concat .= "<tbody><tr> <td style='text-align: center;'>".$datos[3]."</td> </tr></tbody>";
        
        $concat .= "</table>";

        $concat .= "</div>"; 
        
        return $concat;
    }
    
    function verInformacionAccionSecundaria($nro_accion) {
        
        //obtengo informacion de la accion
        $datos = $this->accion_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->accion_ordenes_trabajo_model->verInformacionAccionSecundaria($nro_orden, $nro_accion);
        
        /*
            $retorno[] = $row->nro_cambio;    0
            $retorno[] = $row->nro_parte;     1
            $retorno[] = $row->nombre_parte;  2
            $retorno[] = $row->cantidad;      3
         */
        
        $concat = $this->verInformacionAccionSimple($nro_accion);
        
        $concat .= "<p style='font-weight: bold;'> Detalles de repuestos usados </p>";

        $concat .= "<div class='datagrid'><table><thead><th> Nro cambio </th><th> Nro parte </th><th> Nombre parte </th> <th> Cantidad </th></thead>";  
        
        $j = 0;

        for($i=0; $i<count($datos); $i=$i+4) {
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            } 
            $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$datos[$i]."</td> <td>".$datos[$i+1]."</td> <td>".$datos[$i+2]."</td> <td>".$datos[$i+3]."</td> </tr></tbody>";
            $j++;
        }

        $concat .= "</table>";

        $concat .= "</div>";        
        
        return $concat;
    }
    
    function verInformacionAccionAsociada($nro_accion) {
        
        //obtengo informacion de la accion
        $datos = $this->accion_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->accion_ordenes_trabajo_model->verInformacionAccionAsociada($nro_orden, $nro_accion);
        
        /*
            $retorno[] = $row->nro_cambio;          0
            $retorno[] = $row->nro_pieza_anterior;  1
            $retorno[] = $row->nro_pieza_nueva;     2
            $retorno[] = $row->nro_parte;           3
            $retorno[] = $row->nombre_parte;        4
         */
        
        $concat = $this->verInformacionAccionSimple($nro_accion);
        
        $concat .= "<p style='font-weight: bold;'> Detalles de piezas cambiadas al armamento </p>";

        $concat .= "<div class='datagrid'><table><thead><th> Nro cambio </th><th> Pieza anterior </th><th> Pieza nueva </th> <th> Nro parte </th> <th> Nombre parte </th> </thead>";  
        
        $j = 0;

        for($i=0; $i<count($datos); $i=$i+5) {
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            } 
            $concat .= "<tbody><tr class='".$class."'> <td style='text-align: center;'>".$datos[$i]."</td> <td style='text-align: center;'>".$datos[$i+1]."</td> <td style='text-align: center;'>".$datos[$i+2]."</td> <td>".$datos[$i+3]."</td> <td>".$datos[$i+4]."</td> </tr></tbody>";
            $j++;
        }

        $concat .= "</table>";

        $concat .= "</div>";        
        
        return $concat;        
        
    }
    
    function eliminarAccion() {
        
        $nro_accion = $_POST['nro_accion'];

        $tipo_accion = $this->accion_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
        switch($tipo_accion) {
            
            case 0: //accion simple
                $this->accion_ordenes_trabajo_model->eliminarAccionSimple($nro_accion);
                echo "Accion simple Nro - ".$nro_accion." anulada correctamente";
                break;
            
            case 1: //accion piezas secundarias
                $this->accion_ordenes_trabajo_model->eliminarAccionSecundaria($nro_accion);
                echo "Accion piezas secundarias Nro - ".$nro_accion." anulada correctamente";
                break;
            
            case 2: //accion piezas asociadas
                if($this->accion_ordenes_trabajo_model->hayPiezaCambio($nro_accion)) {
                    $nro_pieza = $this->accion_ordenes_trabajo_model->obtenerPiezaCambio($nro_accion);
                    $nro_pieza_anterior = $this->accion_ordenes_trabajo_model->obtenerPiezaCambioAnterior($nro_accion, $nro_pieza);
                    $this->accion_ordenes_trabajo_model->eliminarAccionAsociada($nro_accion, $nro_pieza_anterior);
                    echo "Accion piezas asociadas Nro - ".$nro_accion." anulada correctamente";
                }else{
                    echo "ERROR: Borrar accion de cambio de pieza no se puede ejecutar, debido a que la pieza del armamento ya no posee dicha pieza";
                }
                break;
        }
    }
    
    function validarDatos() {
        
        $fecha          = $_POST["fecha"];
        $nro_orden      = $_POST["nro_orden"];
        $seccion        = $_POST["seccion"];
        $observaciones  = $_POST["observaciones"];
        
        $mensaje_error = array();
        
        if(empty($fecha)) {
            $mensaje_error[] = 1;
        }
        
        if(empty($nro_orden)) {
            $mensaje_error[] = 2;
        }
        
        if(empty($seccion)) {
            $mensaje_error[] = 3;
        }
        
        if(empty($observaciones)) {
            $mensaje_error[] = 4;
        }        
         
        if(count($mensaje_error) > 0) {
            
            switch($mensaje_error[0]) {
                
                case 1:
                    echo $this->mensajes->errorVacio('fecha');
                    break;
                
                case 2:
                    echo $this->mensajes->errorVacio('nro orden');
                    break;
                
                case 3:
                    echo $this->mensajes->errorVacio('seccion');
                    break;
                
                case 4:
                    echo $this->mensajes->errorVacio('observaciones');
                    break;
            }
        }else {
            $tipo_accion = 0; //accion simple
            $this->accion_ordenes_trabajo_model->altaAccionSimple($fecha, $nro_orden, $seccion, $observaciones, $tipo_accion);
            echo 1;
        }
    }
}

?>
