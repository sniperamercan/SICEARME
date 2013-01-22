<?php

class desarrollo_incremento_version_sistema_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function ingresoDatos($version_actual, $version_nueva) {
        
        $data_version = array(
            'version' => $version_nueva,
            'fecha'   => date('Y-m-d')
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'versiones',
            'clave_tabla'     => 'version_actual = '.$version_actual.', version nueva = '.$version_nueva,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->trans_start();
            $this->db->insert('versiones', $data_version);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();        
        
    }
}

?>
