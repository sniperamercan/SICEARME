<?php

class alta_stock_de_almacen_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function altaStock($nro_parte, $nombre_parte, $precio, $cantidad) {
        
        $data_stock = array(
            'nro_parte'       => $nro_parte,
            'nombre_parte'    => $nombre_parte,
            'precio'          => $precio,
            'cantidad'        => $cantidad,
            'usuario_alta'    => base64_decode($_SESSION['usuario']),
            'usuario_edita'   => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->insert('stock_repuestos', $data_stock);
        
        $query = $this->db->query("SELECT last_insert_id() as nro_interno");
        
        $row = $query->row();
        
        $nro_interno = $row->nro_interno;
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'stock_repuestos',
            'clave_tabla'     => 'nro_interno = '.$nro_interno,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
        
        return $nro_interno;
    }
}

?>
