<?php

class mb_repuestos_nro_pieza extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function cantidadActual($nro_parte) {
        
        $cantidad_actual = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte));
        
        return $cantidad_actual;
    }
    
    function ajusteStock($nro_parte, $cantidad) {
        
        $cantidad_actual = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte));
        
        $data_stock = array(
            'cantidad' => $cantidad_actual - $cantidad
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'stock_repuestos',
            'clave_tabla'     => 'nro_parte = '.$nro_parte,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->update('stock_repuestos', $data_stock);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
