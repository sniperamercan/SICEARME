<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - imprimir_compra
*/

class imprimir_compra extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('imprimir_compra_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 2 y 3 - Usuarios O.C.I y Administradores O.C.I 
        if(!$this->perms->verificoPerfil2() && !$this->perms->verificoPerfil3()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {

        if(isset($_SESSION['imprimir_nro_compra']) && !empty($_SESSION['imprimir_nro_compra'])) {
            $nro_compra = $_SESSION['imprimir_nro_compra'];
        }else {
            $nro_compra = 0;
        }
            
        //Obtengo todos los datos del acta en un array
        $datos_compra = $this->imprimir_compra_model->datosCompra($nro_compra);
        
        /*
            $retorno[] = $row->nro_compra;          1
            $retorno[] = $row->fecha;               2
            $retorno[] = $row->empresa_proveedora;  3
            $retorno[] = $row->pais_empresa;        4
            $retorno[] = $row->descripcion;         5
            $retorno[] = $row->modalidad;           6
            $retorno[] = $row->cantidad_armas;      7
            $retorno[] = $row->precio;              8
         */
        
        //Cargo nro_interno_compra
        $data['nro_interno'] = $nro_compra;
        
        //Cargo nro compra
        $data['nro_compra'] = $datos_compra[0];
        
        //Cargo fecha
        $data['fecha'] = $datos_compra[1];
        
        //Cargo empresa proveedora
        $data['empresa'] = $datos_compra[2];
        
        //Cargo pais empresa
        $data['pais'] = $datos_compra[3];
        
        //Cargo descripcion
        $data['descripcion'] = $datos_compra[4];
        
        //Cargo modalidad
        $data['modalidad'] = $datos_compra[5];
        
        //Cargo total armas
        $data['total_armas'] = $datos_compra[6];
        
        //Cargo precio total
        $data['precio_total'] = $datos_compra[7];
        
        //Armar grilla de catalogos asociados
        
        $datos_catalogos = $this->imprimir_compra_model->datosCatalogos($nro_compra);
        
        /*
            $retorno[] = $row->nro_interno_catalogo;  1
            $retorno[] = $row->tipo_arma;             2
            $retorno[] = $row->marca;                 3
            $retorno[] = $row->calibre;               4
            $retorno[] = $row->modelo;                5
            $retorno[] = $row->sistema;               6
            $retorno[] = $row->cantidad_armas;        7
            $retorno[] = $row->costo;                 8
         */

        $concat = "";
        
        for($i=0; $i<count($datos_catalogos); $i=$i+8) {
        
            $nro_interno       = $datos_catalogos[$i];
            $tipo_arma         = $datos_catalogos[$i+1];
            $marca             = $datos_catalogos[$i+2];
            $calibre           = $datos_catalogos[$i+3];
            $modelo            = $datos_catalogos[$i+4];
            $sistema           = $datos_catalogos[$i+5];
            $cantidad_armas    = $datos_catalogos[$i+6];
            $costo             = $datos_catalogos[$i+7];

            $concat .= "<tr> 
                            <td style='text-align: center;'>".$nro_interno."</td> <td>".$tipo_arma."</td> <td>".$marca."</td> <td>".$calibre."</td> <td>".$modelo."</td> <td>".$sistema."</td> <td style='text-align: center;'>".$cantidad_armas."</td> <td style='text-align: center;'>".$costo."</td>
                       </tr>";            
            }
            
        $data['catalogos_asociados'] = $concat;
            
        //Fin armo grilla de entrega de armamentos    
        
        //Cargo la vista
        $this->load->view("imprimir_compra_view", $data);
    }
    
}

?>
