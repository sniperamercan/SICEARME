<?php

class imprimir_stock_almacen_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function datosStock($nro_parte) {
        
        $query = $this->db->query("SELECT nombre_parte, precio_unitario, cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte));
        
        $retorno = array();
        
        $row = $query->row();
        
        $retorno[] = $row->nombre_parte;
        $retorno[] = $row->precio;
        $retorno[] = $row->cantidad;
       
        return $retorno;
    }
}

?>
