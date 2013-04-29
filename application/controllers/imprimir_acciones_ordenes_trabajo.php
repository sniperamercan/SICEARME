<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - imprimir_acciones_ordenes_trabajo
*/

class imprimir_acciones_ordenes_trabajo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('imprimir_acciones_ordenes_trabajo_model');
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
        
        $data['nro_accion'] = $_SESSION['nro_accion'];
        
        unset($_SESSION['condicion']); //reinicio filtro
        unset($_SESSION['order']); //reinicio el order
        $this->load->view("imprimir_acciones_ordenes_trabajo_view", $data);
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta() {   
        
        $nro_accion = $_SESSION['nro_accion'];
        
        $result = $this->imprimir_acciones_ordenes_trabajo_model->consulta_db_acciones($nro_accion);
        
        $concat ="

            <div class='datagrid'>
            <table>

                <thead style='text-align: center; cursor: pointer;'>
                    <tr>      
                        <th> Fecha        </th>
                        <th> Seccion      </th>
                        <th> Tipo         </th>
                    </tr>
                </thead>

                <tbody>";             

        /* 
         * lo que contiene el array adentro 
        $result[] = $row->nro_accion;
        $result[] = $row->fecha;
        $result[] = $row->seccion;
        $result[] = $row->tipo_accion;       
        */
            
        switch($result[2]) {

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
            <tr>
                <td style='text-align: center;'> ".$result[0]." </td>
                <td> ".$result[1]." </td>
                <td> ".$tipo_accion." </td>
            </tr>
        ";

        $concat .= '</tbody> </table> </div>';

        $concat .= $this->verInformacion($nro_accion);

        $concat .= "<br /> <hr /> </br />";
        
        //Retorno de datos json
        $retorno = array();
        $retorno[] = $concat;
        
        echo json_encode($retorno);
    }    
    
    function verInformacion($nro_accion) {
        
        $tipo_accion = $this->imprimir_acciones_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
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
        
        return $concat;
    }
    
    function verInformacionAccionSimple($nro_accion) {
        
        //obtengo informacion de la accion
        $datos = $this->imprimir_acciones_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
         *  $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */
        
        $concat = "<h2> Detalle de la accion Nro - ".$nro_accion." </h2>";

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
        
        $concat .= "<h3> Detalles </h3>";
        
        $concat .= "<div class='datagrid'><table><thead><th> Detalles </th></thead>";  
        
        $concat .= "<tbody><tr> <td style='text-align: center;'>".$datos[3]."</td> </tr></tbody>";
        
        $concat .= "</table>";

        $concat .= "</div>"; 
        
        return $concat;
    }
    
    function verInformacionAccionSecundaria($nro_accion) {
        
        //obtengo informacion de la accion
        $datos = $this->imprimir_acciones_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->imprimir_acciones_ordenes_trabajo_model->verInformacionAccionSecundaria($nro_orden, $nro_accion);
        
        /*
            $retorno[] = $row->nro_cambio;    0
            $retorno[] = $row->nro_parte;     1
            $retorno[] = $row->nombre_parte;  2
            $retorno[] = $row->cantidad;      3
         */
        
        $concat = $this->verInformacionAccionSimple($nro_accion);
        
        $concat .= "<h3> Detalles de repuestos usados </h3>";

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
        $datos = $this->imprimir_acciones_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->imprimir_acciones_ordenes_trabajo_model->verInformacionAccionAsociada($nro_orden, $nro_accion);
        
        /*
            $retorno[] = $row->nro_cambio;          0
            $retorno[] = $row->nro_pieza_anterior;  1
            $retorno[] = $row->nro_pieza_nueva;     2
            $retorno[] = $row->nro_parte;           3
            $retorno[] = $row->nombre_parte;        4
         */
        
        $concat = $this->verInformacionAccionSimple($nro_accion);
        
        $concat .= "<h3> Detalles de piezas cambiadas al armamento </h3>";

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
