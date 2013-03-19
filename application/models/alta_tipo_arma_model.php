<?php

class alta_tipo_arma_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeTipoArma($tipo_arma) {
        
        $query = $this->db->query("SELECT *
                                   FROM tipos_armas
                                   WHERE tipo_arma = ".$this->db->escape($tipo_arma));
        
        return $query->num_rows();
    }
    
    function altaCatalogo($tipo_arma) {
        
        $data_tipo_arma = array(
            'tipo_arma' => $tipo_arma
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'tipos_armas',
            'clave_tabla'     => 'tipo_arma = '.$tipo_arma,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('tipos_armas', $data_tipo_arma);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
