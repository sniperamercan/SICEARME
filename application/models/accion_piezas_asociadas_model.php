<?php

class accion_piezas_asociadas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function hayDatosAccion($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT *
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)."
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        return $query->num_rows();
    }
    
    function cargoDatosAccion($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT nro_cambio, nro_pieza_nueva, nro_pieza_anterior
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)."
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        $datos = array();
        
        foreach($query->result() as $row) {
            $datos[] = $row->nro_cambio;
            $datos[] = $row->nro_pieza_nueva;
            $datos[] = $row->nro_pieza_anterior;
        }
        
        return $datos;
    }
    
    function altaAccionPiezasAsociadas($nro_pieza_nueva, $nro_pieza_anterior, $nro_orden, $nro_accion, $nro_parte, $nombre_parte, $nro_catalogo) {
        
        $this->db->trans_start();
        
            $data_accion = array(
                'nro_orden'          => $nro_orden,
                'nro_accion'         => $nro_accion,
                'nro_pieza_anterior' => $nro_pieza_anterior,
                'nro_pieza_nueva' => $nro_pieza_nueva
            );
        
            $this->db->insert('cambio_piezas_asociadas_ordenes_trabajo', $data_accion);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'insert',
                'tabla'           => 'cambio_piezas_asociadas_ordenes_trabajo',
                'clave_tabla'     => 'nro_orden = '.$nro_orden.', nro_accion = '.$nro_accion,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);            
            
            $data_stock_where = array(
                'nro_pieza'            => $nro_pieza_nueva,
                'nro_interno_catalogo' => $nro_catalogo,
                'nro_parte'            => $nro_parte,
                'nombre_parte'         => $nombre_parte
            );
            
            $this->db->delete('stock_repuestos_nro_pieza', $data_stock_where);

            $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                       FROM ordenes_trabajo
                                       WHERE nro_orden = ".$this->db->escape($nro_orden));
            
            $row = $query->row();
            
            $data_ficha_set = array(
                'nro_pieza' => $nro_pieza_nueva
            );
            
            $data_ficha_where = array(
                'nro_serie' => $row->nro_serie,
                'marca'     => $row->marca,
                'calibre'   => $row->calibre,
                'modelo'    => $row->modelo
            );
            
            $this->db->update('fichas_piezas', $data_ficha_set, $data_ficha_where);
            
            $data_db_logs = array(
                'tipo_movimiento' => 'update',
                'tabla'           => 'fichas_piezas',
                'clave_tabla'     => 'nro_pieza = '.$nro_pieza_nueva,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);
        
        $this->db->trans_complete();
    }
    
}

?>
