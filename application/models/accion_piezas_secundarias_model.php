<?php

class accion_piezas_secundarias_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoCantidad($nro_parte, $nombre_parte, $nro_catalogo) {
        
        $query = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)."
                                   AND nombre_parte = ".$this->db->escape($nombre_parte)."
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        return $row->cantidad;
    }
    
    function hayDatosAccion($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT *
                                   FROM cambio_piezas_no_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)."
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        return $query->num_rows();
    }
    
    function cargoNroCatalogo($nro_orden) {
        
        $query = $this->db->query("SELECT f.nro_interno_catalogo
                                   FROM fichas f
                                   INNER JOIN ordenes_trabajo o ON f.nro_serie = o.nro_serie AND f.marca = o.marca AND f.calibre = o.calibre AND f.modelo = o.modelo
                                   WHERE o.nro_orden = ".$this->db->escape($nro_orden));
        
        $row = $query->row();
        
        return $row->nro_interno_catalogo;
    }
    
    function cargoDatosAccion($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT nro_cambio, nro_parte, nombre_parte, nro_interno_catalogo, cantidad
                                   FROM cambio_piezas_no_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)."
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        $datos = array();
        
        foreach($query->result() as $row) {
            $datos[] = $row->nro_cambio;
            $datos[] = $row->nro_parte;
            $datos[] = $row->nombre_parte;
            $datos[] = $row->nro_interno_catalogo;
            $datos[] = $row->cantidad;
        }
        
        return $datos;
    }
    
    function cargoDatosAccionEliminar($nro_cambio) {
        
        $query = $this->db->query("SELECT nro_parte, nombre_parte, cantidad
                                   FROM cambio_piezas_no_asociadas_ordenes_trabajo
                                   WHERE nro_cambio = ".$this->db->escape($nro_cambio));
        
        $datos = array();
        
        $row = $query->row();
        
        $datos[] = $row->nro_parte;
        $datos[] = $row->nombre_parte;
        $datos[] = $row->cantidad;
        
        return $datos;
    }
    
    function eliminarAccionSecundaria($nro_cambio, $nro_parte, $nombre_parte, $cantidad) {
        
        $data_stock_set = array(
            'cantidad' => $cantidad
        );
        
        $data_stock_where = array(
            'nro_parte'    => $nro_parte,
            'nombre_parte' => $nombre_parte
        );
        
        $this->db->update('stock_repuestos', $data_stock_set, $data_stock_where);
        
        $data_cambio_where = array(
            'nro_cambio' => $nro_cambio
        );
        
        $this->db->delete('cambio_piezas_no_asociadas_ordenes_trabajo', $data_cambio_where);
    }
    
    function altaAccionPiezasSecundarias($nro_parte, $nombre_parte, $cant_usar, $cant_total) {
        
        $this->db->trans_start();
        
            $data_accion_set = array(
                'cantidad' => $cant_total
            );

            $data_accion_where = array(
                'nro_parte'    => $nro_parte,
                'nombre_parte' => $nombre_parte
            );

            $this->db->update('stock_repuestos', $data_accion_set, $data_accion_where);

            $data_db_logs = array(
                'tipo_movimiento' => 'update',
                'tabla'           => 'stock_repuestos',
                'clave_tabla'     => 'nro_parte = '.$nro_parte.', nombre parte = '.$nombre_parte,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);

            $data_accion = array(
                'nro_orden'    => $_SESSION['nro_orden'],
                'nro_accion'   => $_SESSION['nro_accion'],
                'nro_parte'    => $nro_parte,
                'nombre_parte' => $nombre_parte,
                'cantidad'     => $cant_usar
            );

            $this->db->insert('cambio_piezas_no_asociadas_ordenes_trabajo', $data_accion);
        
        $this->db->trans_complete();
    }
    
}

?>