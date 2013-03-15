<?php

class listado_actas_baja_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }   
    
    function listadoFichas() {
        
        $query = $this->db->query("SELECT nro_serie, marca, modelo, calibre, nro_interno_compra
                                   FROM fichas
                                   ORDER BY nro_serie");
        
        $fichas = array();
        
        foreach($query->result() as $row) {
            $fichas[]  = $row->nro_serie;
            $fichas[]  = $row->marca;
            $fichas[]  = $row->modelo;
            $fichas[]  = $row->calibre;
            $fichas[]  = $row->nro_interno_compra;
        }
        
        return $fichas;
    }
}

?>
