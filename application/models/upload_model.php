<?php

class upload_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoEmpresas() {
        
        $query = $this->db->query("SELECT nro_interno
                                   FROM catalogos
                                   ORDER BY nro_interno");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->rut;
        }
        
        return $result;
    }
    
}

?>
