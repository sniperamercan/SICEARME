<?php

class modificar_estado_orden_trabajo_model extends CI_Model {
    
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
    
    function cambiarEstadoOrdenTrabajo($nro_orden, $tipo_estado) {
        
        $data_where = array(
            'nro_orden' => $nro_orden
        );
        
        $data_set = array(
            'estado_arma'          => $tipo_estado,
            'estado_orden_trabajo' => 1
        );        
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'ordenes_trabajo',
            'clave_tabla'     => 'nro_orden = '.$nro_orden,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->update('ordenes_trabajo', $data_set, $data_where);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
