<?php

class upload_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoCatalogos() {
        
        $query = $this->db->query("SELECT nro_interno
                                   FROM catalogos
                                   ORDER BY nro_interno");
        
        $catalogos = array();
        
        foreach($query->result() as $row) {
            $catalogos[] = $row->nro_interno;
        }
        
        return $catalogos;
    }
    
}

?>
