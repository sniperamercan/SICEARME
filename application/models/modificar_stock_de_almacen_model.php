<?php

class modificar_stock_de_almacen_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function obtengoDatos($nro_parte, $nombre_parte, $nro_catalogo) {
        
        $query = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos 
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)."
                                   AND nombre_parte = ".$this->db->escape($nombre_parte)."
                                   AND nro_interno_catalogo = ".$this->db->escape($nro_catalogo));
        
        $row = $query->row();
        
        return $row->cantidad;
    }

    function modificarRepuesto($nro_parte, $nombre_parte, $nro_catalogo, $nro_parte_ant, $nombre_parte_ant, $nro_catalogo_ant) {
        
        $cantidad = $this->cantidadActual($nro_parte_ant, $nombre_parte_ant, $nro_catalogo_ant);
        
        $data_stock_where = array(
            'nro_parte'            => $nro_parte_ant,
            'nombre_parte'         => $nombre_parte_ant,
            'nro_interno_catalogo' => $nro_catalogo_ant
        );
        
        $this->db->delete("stock_repuestos", $data_stock_where);
        
        $data_stock = array(
            'nro_parte'            => $nro_parte,
            'nombre_parte'         => $nombre_parte,
            'nro_interno_catalogo' => $nro_catalogo,
            'cantidad'             => $cantidad
        );
        
        $this->db->insert('stock_repuestos', $data_stock);  
        
        $data_stock_set = array(
            'nro_parte'            => $nro_parte,
            'nombre_parte'         => $nombre_parte,
            'nro_interno_catalogo' => $nro_catalogo
        );        
        
        $this->db->update('ingreso_stock_repuestos', $data_stock_set, $data_stock_where);   
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
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
