<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - imprimir_stock_almacen
*/

class imprimir_cambios_piezas_asociadas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('imprimir_cambios_piezas_asociadas_model');
        $this->load->library('perms');
        $this->load->library('pagination');   
        $this->load->library('mensajes');
        $this->load->library('form_validation'); 
        
        if(!$this->perms->VerificoUsuario()){
            die($this->mensajes->sinPermisos());
        }
        
        //Modulo solo visible para el peril 9 y 10 - Usuarios taller de armamento y Administradores taller de armamento  
        if(!$this->perms->verificoPerfil9() && !$this->perms->verificoPerfil10()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {

        $nro_cambio  = $_SESSION['nro_cambio'];
        
        $datos = $this->imprimir_cambios_piezas_asociadas_model->datosCambio($nro_cambio);
        
        /*
        $result[] = $row->nro_orden;
        $result[] = $row->nro_serie;
        $result[] = $row->marca;
        $result[] = $row->calibre;
        $result[] = $row->modelo;
        $result[] = $row->nro_pieza_anterior;
        $result[] = $row->nro_pieza_nueva;
        $result[] = $row->nro_parte;
        $result[] = $row->nombre_parte;
        $result[] = $row->nro_cambio;
         */
        
        $nro_orden           = $datos[0];
        $nro_serie           = $datos[1];
        $marca               = $datos[2];
        $calibre             = $datos[3];
        $modelo              = $datos[4];
        $nro_pieza_anterior  = $datos[5];
        $nro_pieza_nueva     = $datos[6];
        $nro_parte           = $datos[7];
        $nombre_parte        = $datos[8];
        
        $concat = "
            <tr> 
                <td style='text-align: center;'> ".$nro_orden." </td>
                <td style='text-align: center;'> ".$nro_serie." </td>
                <td> ".$marca." </td>
                <td> ".$calibre." </td>
                <td> ".$modelo." </td>
                <td> ".$calibre." </td>
                <td style='text-align: center;'> ".$nro_pieza_anterior." </td>
                <td style='text-align: center;'> ".$nro_pieza_nueva." </td>    
                <td> ".$nro_parte." </td>
                <td> ".$nombre_parte." </td>
            </tr>
        ";
        
        $data['contenido'] = $concat;
        
        //Cargo la vista
        $this->load->view("imprimir_cambios_piezas_asociadas", $data);
    }
    
}

?>
