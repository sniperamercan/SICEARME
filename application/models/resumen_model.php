<?php

class resumen_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function armoGraficas1_db() {
        
        $query = $this->db->query("SELECT producto ,SUM(cantidad_cajas) AS suma
                                   FROM produccion
                                   GROUP BY producto
                                   ORDER BY suma DESC LIMIT 5");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->producto;
            $result[] = $row->suma;
        }
        
        return $result;
    }

    function armoGraficas2_db() {
        
        $query = $this->db->query("SELECT producto ,SUM(cant_entregar) AS suma
                                   FROM salida_producto_terminado
                                   GROUP BY producto
                                   ORDER BY suma DESC LIMIT 5");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->producto;
            $result[] = $row->suma;
        }
        
        return $result;
    }
    
    function armoGraficas3_db() {
        
        $query = $this->db->query("SELECT producto ,SUM(cant_entregar) AS suma
                                   FROM salida_producto_terminado_unidades
                                   GROUP BY producto
                                   ORDER BY suma DESC LIMIT 5");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->producto;
            $result[] = $row->suma;
        }
        
        return $result;
    }    
    
}

?>
