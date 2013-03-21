<?php

class alta_tipo_pieza_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeTipoPieza($tipo_pieza) {
        
        $query = $this->db->query("SELECT *
                                   FROM tipos_piezas
                                   WHERE tipo_pieza = ".$this->db->escape($tipo_pieza));
        
        return $query->num_rows();
    }
    
    function altaTipoPieza($tipo_pieza) {
        
        $data_tipo_pieza = array(
            'tipo_pieza' => $tipo_pieza
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'tipos_piezas',
            'clave_tabla'     => 'tipo_pieza = '.$tipo_pieza,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('tipos_piezas', $data_tipo_pieza);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
