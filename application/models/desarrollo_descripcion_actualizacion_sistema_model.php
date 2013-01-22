<?php

class desarrollo_descripcion_actualizacion_sistema_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function getVersion() {
        
        $query = $this->db->query("SELECT MAX(version) AS version
                                   FROM versiones");
        
        $row = $query->row();
        
        return $row->version;
    }
    
    function ingresarDatos($version, $fecha, $descripcion, $critica) {
        
        $data_descripcion_version = array(
            'version'       => $version,
            'fecha'         => $fecha,
            'descripcion'   => $descripcion,
            'critica'       => $critica
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'descripcion_version',
            'clave_tabla'     => 'version = '.$version.', fecha = '.$fecha,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('descripcion_version', $data_descripcion_version);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();
    }
}

?>
