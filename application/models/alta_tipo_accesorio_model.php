<?php

class alta_tipo_accesorio_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeTipoAccesorio($tipo_accesorio) {
        
        $query = $this->db->query("SELECT *
                                   FROM tipos_accesorios
                                   WHERE tipo_accesorio = ".$this->db->escape($tipo_accesorio));
        
        return $query->num_rows();
    }
    
    function altaTipoAccesorio($tipo_accesorio) {
        
        $data_tipo_accesorio = array(
            'tipo_accesorio' => $tipo_accesorio
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'tipos_accesorios',
            'clave_tabla'     => 'tipo_accesorio = '.$tipo_accesorio,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('tipos_accesorios', $data_tipo_accesorio);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
