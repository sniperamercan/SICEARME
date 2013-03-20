<?php

class alta_calibre_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeCalibre($calibre) {
        
        $query = $this->db->query("SELECT *
                                   FROM calibres
                                   WHERE calibre = ".$this->db->escape($calibre));
        
        return $query->num_rows();
    }
    
    function altaCalibre($calibre) {
        
        $data_calibre = array(
            'calibre' => $calibre
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'calibres',
            'clave_tabla'     => 'calibre = '.$calibre,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('calibre', $data_calibre);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
