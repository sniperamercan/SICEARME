<?php

class imprimir_stock_almacen_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function cantidadActual($nro_parte, $nombre_parte, $nro_catalogo) {
        
        $query = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)."
                                   AND nombre_parte = ".$this->db->escape($nombre_parte)." 
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        return $row->cantidad;
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
