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
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'stock_repuestos',
            'clave_tabla'     => 'nro_parte = '.$nro_parte.', nombre_parte ='.$nombre_parte,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
    }
    
    function actualizoCantidad($nro_parte, $nombre_parte, $precio, $cantidad) {
        
        $data_stock_where = array(
            'nro_parte'    => $nro_parte,
            'nombre_parte' => $nombre_parte
        );
        
        $cantidad_total = $cantidad + $this->catidadActual($nro_parte, $nombre_parte);
        
        $data_stock_set = array(
            'cantidad' => $cantidad_total
        );
        
        $this->db->update("stock_repuestos", $data_stock_set, $data_stock_where);
                
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'stock_repuestos',
            'clave_tabla'     => 'nro_parte = '.$nro_parte.', nombre_parte ='.$nombre_parte,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);                
    }
    
    function catidadActual($nro_parte, $nombre_parte) {
        
        $query = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)."
                                   AND nombre_parte = ".$this->db->escape($nombre_parte));
        
        $row = $query->row();
        
        return $row->cantidad;
    }
    
    function existeParte($nro_parte, $nombre_parte) {
        
        $query = $this->db->query("SELECT *
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)."
                                   AND nombre_parte = ".$this->db->escape($nombre_parte));
        
        return $query->num_rows();
    }
}

?>
