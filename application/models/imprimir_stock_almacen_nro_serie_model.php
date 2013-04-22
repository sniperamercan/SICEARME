<?php

class imprimir_stock_almacen_nro_serie_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function datosStock($nro_parte) {
        
        $query = $this->db->query("SELECT nro_parte, nombre_parte, precio_unitario, cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte));
        
        $retorno = array();
        
        $row = $query->row();
        
        $retorno[] = $row->nro_parte;
        $retorno[] = $row->nombre_parte;
        $retorno[] = $row->precio;
        $retorno[] = $row->cantidad;
       
        return $retorno;
    }
}

?>
