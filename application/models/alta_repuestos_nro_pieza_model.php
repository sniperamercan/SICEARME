<?php

class alta_repuestos_nro_pieza_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function altaRepuestoNroPieza($nro_pieza, $nro_parte, $nombre_parte, $cant_actual, $nro_catalogo) {
        
        $this->db->trans_start();
        
            $cant_total = $cant_actual - 1;
        
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

            $data_stock = array(
                'nro_pieza'              => $nro_pieza,
                'nro_interno_catalogo'   => $nro_catalogo,
                'nro_parte'              => $nro_parte,
                'nombre_parte'           => $nombre_parte
            );

            $this->db->insert('stock_repuestos_nro_pieza', $data_stock);
        
        $this->db->trans_complete();
    }
    
}

?>