<?php

/*
* Equipo - UDEPGCALIT
* Año - 2013
* Iteracion - Primera Iteracion
* Clase - imprimir_stock_almacen_nro_serie
*/

class imprimir_stock_almacen_nro_serie extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('imprimir_stock_almacen_nro_serie_model');
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

        $nro_pieza       = $_SESSION['nro_pieza'];
        $nro_parte       = $_SESSION['nro_parte'];
        $nombre_parte    = $_SESSION['nombre_parte'];
        $nro_catalogo    = $_SESSION['nro_catalogo'];
        
        $datos = $this->imprimir_stock_almacen_nro_serie_model->datosCatalogo($nro_catalogo);
        
        $tipo_arma = $datos[0];
        $marca     = $datos[1];
        $calibre   = $datos[2];
        $modelo    = $datos[3];
        
        $concat = "
            <tr> 
                <td style='text-align: center;'> ".$nro_pieza." </td>
                <td> ".$nro_parte." </td>
                <td> ".$nombre_parte." </td>
                <td style='text-align: center;'> ".$nro_catalogo." </td>
                <td> ".$tipo_arma." </td>
                <td> ".$marca." </td>
                <td> ".$calibre." </td>
                <td> ".$modelo." </td>
            </tr>
        ";
        
        $data['contenido'] = $concat;
        
        //Cargo la vista
        $this->load->view("imprimir_stock_almacen_nro_serie_view", $data);
    }
    
}

?>
