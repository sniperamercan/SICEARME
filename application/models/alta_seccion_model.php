<?php

class alta_seccion_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeSeccion($seccion) {
        
        $query = $this->db->query("SELECT *
                                   FROM secciones
                                   WHERE seccion = ".$this->db->escape($seccion));
        
        return $query->num_rows();
    }
    
    function altaSeccion($seccion) {
        
        $data_seccion = array(
            'seccion' => $seccion
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'secciones',
            'clave_tabla'     => 'seccion = '.$seccion,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('secciones', $data_seccion);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
