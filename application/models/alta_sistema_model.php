<?php

class alta_sistema_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeSistema($sistema) {
        
        $query = $this->db->query("SELECT *
                                   FROM sistemas
                                   WHERE sistema = ".$this->db->escape($sistema));
        
        return $query->num_rows();
    }
    
    function altaSistema($sistema) {
        
        $data_sistema = array(
            'sistema' => $sistema
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'sistemas',
            'clave_tabla'     => 'sistema = '.$sistema,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('sistemas', $data_sistema);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
