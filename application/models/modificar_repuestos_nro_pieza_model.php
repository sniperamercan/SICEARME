<?php

class modificar_repuestos_nro_pieza_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoCantidad($nro_parte, $nombre_parte, $nro_catalogo) {
        
        $query = $this->db->query("SELECT cantidad 
                                   FROM stock_repuestos
                                   WHERE nro_parte =".$this->db->escape($nro_parte)."
                                   AND nombre_parte =".$this->db->escape($nombre_parte)."
                                   AND nro_interno_catalogo =".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        return $row->cantidad;
    }
    
    function existePieza($nro_pieza, $nro_parte, $nombre_parte, $nro_catalogo) {
        
        $query = $this->db->query("SELECT *
                                   FROM stock_repuestos_nro_pieza
                                   WHERE nro_pieza =".$this->db->escape($nro_pieza)."
                                   AND nro_parte =".$this->db->escape($nro_parte)."
                                   AND nombre_parte =".$this->db->escape($nombre_parte)."
                                   AND nro_interno_catalogo =".$this->db->escape($nro_catalogo));
        
        return $query->num_rows();
    }    
    
    function modificarRepuestoNroPieza($nro_pieza, $nro_parte, $nombre_parte, $nro_catalogo, $nro_pieza_anterior) {
        
        $this->db->trans_start();
        
            $data_accion_set = array(
                'nro_pieza' => $nro_pieza
            );

            $data_accion_where = array(
                'nro_pieza'            => $nro_pieza_anterior,
                'nro_parte'            => $nro_parte,
                'nombre_parte'         => $nombre_parte,
                'nro_interno_catalogo' => $nro_catalogo
            );

            $this->db->update('stock_repuestos_nro_pieza', $data_accion_set, $data_accion_where);

            $data_db_logs = array(
                'tipo_movimiento' => 'update',
                'tabla'           => 'stock_repuestos_nro_pieza',
                'clave_tabla'     => 'nro_pieza = '.$nro_pieza.', nro_parte = '.$nro_parte.', nombre parte = '.$nombre_parte.', nro_catalogo = '.$nro_catalogo,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);
        
        $this->db->trans_complete();
    }
}

?>