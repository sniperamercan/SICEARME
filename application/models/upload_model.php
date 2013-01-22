<?php

class upload_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoEmpresas() {
        
        $query = $this->db->query("SELECT rut
                                   FROM empresas
                                   ORDER BY rut");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->rut;
        }
        
        return $result;
    }
    
}

?>
