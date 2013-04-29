<?php

class modificar_stock_de_almacen_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function modificarStock($nro_parte, $nombre_parte, $precio, $cantidad, $nro_catalogo) {
        
        $data_stock = array(
            'nro_parte'            => $nro_parte,
            'nombre_parte'         => $nombre_parte,
            'nro_interno_catalogo' => $nro_catalogo,
            'cantidad'             => $cantidad
        );
        
        $this->db->upadte('stock_repuestos', $data_stock);
        
        $data_ingreso_stock = array(
            'nro_parte'            => $nro_parte,
            'nombre_parte'         => $nombre_parte,
            'nro_interno_catalogo' => $nro_catalogo,
            'precio_unitario'      => $precio,
            'cantidad'             => $cantidad,
            'usuario'              => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->update('ingreso_stock_repuestos', $data_ingreso_stock);        
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'stock_repuestos',
            'clave_tabla'     => 'nro_parte = '.$nro_parte.', nombre_parte ='.$nombre_parte.', nro_interno_catalogo ='.$nro_catalogo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
    }
    
    function actualizoStock($nro_parte, $nombre_parte, $precio, $cantidad, $nro_catalogo) {
        
        $data_stock_where = array(
            'nro_parte'            => $nro_parte,
            'nombre_parte'         => $nombre_parte,
            'nro_interno_catalogo' => $nro_catalogo
        );
        
        $cantidad_total = $cantidad + $this->cantidadActual($nro_parte, $nombre_parte, $nro_catalogo);
        
        $data_stock_set = array(
            'cantidad' => $cantidad_total
        );
        
        $this->db->update("stock_repuestos", $data_stock_set, $data_stock_where);
                
        $data_detalle_stock = array(
            'nro_parte'            => $nro_parte,
            'nombre_parte'         => $nombre_parte,
            'nro_interno_catalogo' => $nro_catalogo,
            'precio_unitario'      => $precio,
            'cantidad'             => $cantidad,
            'usuario'              => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->update('ingreso_stock_repuestos', $data_detalle_stock);         
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'stock_repuestos',
            'clave_tabla'     => 'nro_parte = '.$nro_parte.', nombre_parte ='.$nombre_parte.', nro_interno_catalogo ='.$nro_catalogo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);                
    }
    
    function cantidadActual($nro_parte, $nombre_parte, $nro_catalogo) {
        
        $query = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)."
                                   AND nombre_parte = ".$this->db->escape($nombre_parte)." 
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        return $row->cantidad;
    }
    
    function existeParte($nro_parte, $nombre_parte, $nro_catalogo) {
        
        $query = $this->db->query("SELECT *
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)."
                                   AND nombre_parte = ".$this->db->escape($nombre_parte)." 
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        return $query->num_rows();
    }
}

?>
