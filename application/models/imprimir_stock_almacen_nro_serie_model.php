<?php

class imprimir_stock_almacen_nro_serie_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function datosCatalogo($nro_catalogo) {
        
        $query = $this->db->query("SELECT tipo_arma, marca, calibre, modelo
                                   FROM catalogos
                                   WHERE nro_interno = ".$this->db->escape($nro_catalogo));
        
        $datos = array();
        
        $row = $query->row();
        
        $datos[] = $row->tipo_arma;
        $datos[] = $row->marca;
        $datos[] = $row->calibre;
        $datos[] = $row->modelo;
        
        return $datos;
    }
}

?>
