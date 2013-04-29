<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - listado_de_una_accion_ordenes_trabajo
*/

class listado_de_una_accion_ordenes_trabajo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('listado_de_una_accion_ordenes_trabajo_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 6 y 7 - Usuario Taller de armamento y Administrador Taller de armamento
        if(!$this->perms->verificoPerfil6() && !$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {
        $data['nro_orden'] = $_SESSION['nro_orden'];
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("listado_de_una_accion_ordenes_trabajo_view", $data);
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta() {   
        
        $nro_orden = $_SESSION['nro_orden'];
        
        $result = $this->listado_de_una_accion_ordenes_trabajo_model->consulta_db($nro_orden);
          
        $j=0;
        
        $concat = "";
        
        for($i=0;$i<count($result);$i=$i+4) {
            
            if($j % 2 == 0){
                $class = "";
            }else{
                $class = "alt";
            }                        
            
            $aux_nro_accion = '"'.$result[$i].'"';
            /* 
             * lo que contiene el array adentro 
            $result[] = $row->nro_accion;
            $result[] = $row->fecha;
            $result[] = $row->seccion;
            $result[] = $row->tipo_accion;       
            */
            
            switch($result[$i+3]) {

                case 0: //accion simple
                    $tipo_accion = "accion simple";
                    break;

                case 1: //accion piezas secundarias
                    $tipo_accion = "accion piezas secundarias";
                    break;

                case 2: //accion piezas asociadas
                    $tipo_accion = "accion piezas asociadas";
                    break;
            }      
            
            $concat .= "
                <tr class='".$class."'>
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$tipo_accion." </td>
                    <td onclick='verInformacion(".$aux_nro_accion.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/eye.png' /> </td>
                    <td onclick='imprimirAccion(".$aux_nro_accion.");' style='text-align: center; cursor: pointer;'> <img src='".base_url()."images/print.png' /> </td>
                </tr>
            ";
            
            $j++;
        }                  
        
        //Retorno de datos json
        $retorno = array();
        $retorno[] = $concat;
        
        echo json_encode($retorno);
    }    
    
    function verInformacion() {
        
        $nro_accion = $_POST['nro_accion'];

        $tipo_accion = $this->listado_de_una_accion_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
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
        $datos = $this->listado_de_una_accion_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
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
        $datos = $this->listado_de_una_accion_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->listado_de_una_accion_ordenes_trabajo_model->verInformacionAccionSecundaria($nro_orden, $nro_accion);
        
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
        $datos = $this->listado_de_una_accion_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->listado_de_una_accion_ordenes_trabajo_model->verInformacionAccionAsociada($nro_orden, $nro_accion);
        
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
}

?>
