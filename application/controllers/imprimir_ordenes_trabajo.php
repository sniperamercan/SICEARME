<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Segunda Iteracion
* Clase - imprimir_ordenes_trabajo
*/

class imprimir_ordenes_trabajo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('imprimir_ordenes_trabajo_model');
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
        $this->load->view("imprimir_ordenes_trabajo_view", $data);
    }
    
    //cantReg = cantidad de registros x pagina
    function consulta() {   
        
        $nro_orden = $_SESSION['nro_orden'];
        
        $concat = '
            <div class="datagrid">
            <table>

                <thead style="text-align: center; cursor: pointer;">
                    <tr>      
                        <th> Fecha          </th>
                        <th> Nro serie      </th>
                        <th> Marca          </th>
                        <th> Calibre        </th>
                        <th> Modelo         </th>
                        <th> Unidad         </th>
                        <th> Estado Orden   </th>
                        <th> Estado arma    </th>
                    </tr>
                </thead>

                <tbody id="datos_consulta">';  
            
        $result = $this->imprimir_ordenes_trabajo_model->consulta_db($nro_orden);

        /* 
         * lo que contiene el array adentro 
        $result[] = $row->fecha;
        $result[] = $row->nro_serie;
        $result[] = $row->marca;
        $result[] = $row->calibre;
        $result[] = $row->modelo;
        $result[] = $row->nombreunidad;
        $result[] = $row->estado_orden_trabajo;   
        $result[] = $row->estado_arma;        
        */        
        
        switch($result[6]) {
            case 0:
                $estado_orden_trabajo = "abierta";
                break;

            case 1:
                $estado_orden_trabajo = "cerrada";
                break;
        }  
        
        $estado_arma = "-";

        switch($result[7]) {

            case "reparado":
                $estado_arma = "<font color='green'> reparado </font>";
                break;

            case "reparado con desperfectos":
                $estado_arma = "<font color='#AEB404'> con desperfectos </font>";
                break;

            case "sin reparacion":
                $estado_arma = "<font color='red'> sin reparacion </font>";
                break;  
        }        
        
        $concat .= "
            <tr> 
                <td style='text-align: center;'> ".$result[0]." </td>
                <td> ".$result[1]." </td>
                <td> ".$result[2]." </td>
                <td> ".$result[3]." </td>
                <td> ".$result[4]." </td>
                <td> ".$result[5]." </td>
                <td> ".$estado_orden_trabajo." </td>
                <td style='font-weight: bold;'> ".$estado_arma." </td>
            </tr>
        ";   
        
        $concat .= '</tbody> </table> </div> <br /> <hr /> <br /> ';
        
        $result = $this->imprimir_ordenes_trabajo_model->consulta_db_acciones($nro_orden);
        
        for($i=0;$i<count($result);$i=$i+4) {
            
            $concat .="

                <div class='datagrid'>
                <table>

                    <thead style='text-align: center; cursor: pointer;'>
                        <tr>      
                            <th> Nro accion    </th>
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
                <tr>
                    <td style='text-align: center;'> ".$result[$i]." </td>
                    <td style='text-align: center;'> ".$result[$i+1]." </td>
                    <td> ".$result[$i+2]." </td>
                    <td> ".$tipo_accion." </td>
                </tr>
            ";
            
            $concat .= '</tbody> </table> </div>';
            
            $concat .= $this->verInformacion($result[$i]);
      
            $concat .= "<br /> <hr /> </br />";
            
        }      
        
        //Retorno de datos json
        $retorno = array();
        $retorno[] = $concat;
        
        echo json_encode($retorno);
    }    
    
    function verInformacion($nro_accion) {
        
        $tipo_accion = $this->imprimir_ordenes_trabajo_model->cargoTipoAccion($nro_accion);
        
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
        $datos = $this->imprimir_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
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
        $datos = $this->imprimir_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->imprimir_ordenes_trabajo_model->verInformacionAccionSecundaria($nro_orden, $nro_accion);
        
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
        $datos = $this->imprimir_ordenes_trabajo_model->verInformacionAccionSimple($nro_accion);
        
        /*
            $retorno[] = $row->nro_orden;   0
            $retorno[] = $row->fecha;       1
            $retorno[] = $row->seccion;     2
            $retorno[] = $row->detalles;    3
            $retorno[] = $row->tipo_accion; 4
         */  
        
        $nro_orden = $datos[0];
        
        $datos = $this->imprimir_ordenes_trabajo_model->verInformacionAccionAsociada($nro_orden, $nro_accion);
        
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
