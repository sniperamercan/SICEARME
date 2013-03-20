<?php

class alta_modelo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeModelo($modelo) {
        
        $query = $this->db->query("SELECT *
                                   FROM modelos
                                   WHERE modelo = ".$this->db->escape($modelo));
        
        return $query->num_rows();
    }
    
    function altaModelo($modelo) {
        
        $data_modelo = array(
            'modelo' => $modelo
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'modelos',
            'clave_tabla'     => 'modelo = '.$modelo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('modelos', $data_modelo);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
