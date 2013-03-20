<?php

class alta_marca_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeMarca($marca) {
        
        $query = $this->db->query("SELECT *
                                   FROM marcas
                                   WHERE marca = ".$this->db->escape($marca));
        
        return $query->num_rows();
    }
    
    function altaMarca($marca) {
        
        $data_marca = array(
            'marca' => $marca
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'marcas',
            'clave_tabla'     => 'marca = '.$marca,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('marca', $data_marca);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
