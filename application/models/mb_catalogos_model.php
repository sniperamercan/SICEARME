<?php

class mb_catalogos_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }   
    
    function listadoCatalogos() {
        
        $query = $this->db->query("SELECT nro_interno, fecha_creacion, cantidad_armas, tipo_arma, marca, modelo, calibre, sistema, año_fabricacion, pais_origen, garantia
                                   FROM catalogos
                                   ORDER BY nro_interno");
        
        $catalogos = array();
        
        foreach($query->result() as $row) {
            $catalogos[]  = $row->nro_interno;
            $catalogos[]  = $row->fecha_creacion;
            $catalogos[]  = $row->cantidad_armas;
            $catalogos[]  = $row->tipo_arma;
            $catalogos[]  = $row->marca;
            $catalogos[]  = $row->modelo;
            $catalogos[]  = $row->calibre;
            $catalogos[]  = $row->sistema;
            $catalogos[]  = $row->año_fabricacion;
            $catalogos[]  = $row->pais_origen;
            $catalogos[]  = $row->garantia;
        }
        
        return $catalogos;
    }
}

?>
