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
        
        //Modulo solo visible para el peril 6 y 7 - Usuarios taller de armamento y Administradores taller de armamento  
        if(!$this->perms->verificoPerfil6() && !$this->perms->verificoPerfil7()) {
            die($this->mensajes->sinPermisos());
        }        
    }
    
    function index() {

        if(isset($_SESSION['imprimir_nro_parte']) && !empty($_SESSION['imprimir_nro_parte'])) {
            $nro_catalogo = $_SESSION['imprimir_nro_parte'];
        }else {
            $nro_catalogo = 0;
        }
            
        //Obtengo todos los datos del stock en un array
        $datos_catalogo = $this->imprimir_stock_almacen_nro_serie_model->datosStock($nro_parte);
        
        /*
            $retorno[] = $row->nro_parte;       1
            $retorno[] = $row->nombre_parte;    2
            $retorno[] = $row->precio;          3
            $retorno[] = $row->cantidad;        4
         */
        
        $data['nro_parte']       = $nro_parte;
        $data['nombre_parte']    = $datos_catalogo[0];
        $data['precio']          = $datos_catalogo[1];
        $data['cantidad']        = $datos_catalogo[2];
            
        
        //Cargo la vista
        $this->load->view("imprimir_stock_almacen_nro_serie_view", $data);
    }
    
}

?>
