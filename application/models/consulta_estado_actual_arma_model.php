<?php

class consulta_estado_actual_arma_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoUnidades() {
        
        $query = $this->db->query("SELECT idunidad, nombreunidad
                                   FROM unidades
                                   ORDER BY nombreunidad");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->idunidad;
            $result[] = $row->nombreunidad;
        }
        
        return $result;
    }
    
    //para paginado
    function cantidadRegistros($condicion) {
        $query = $this->db->query("SELECT *
                                   FROM ordenes_trabajo
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order) {
        
        $result = array();

        $query = $this->db->query("SELECT MAX(nro_orden) AS nro_orden
                                   FROM ordenes_trabajo
                                   WHERE ".$condicion);
                                   //LIMIT ".$ini.",".$param);
        
        $row = $query->row();
        
        return $row->nro_orden;
    } 
    
    function estadoOrdenTrabajo($nro_orden) {
        
        $query = $this->db->query("SELECT estado_arma
                                   FROM ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden));
        
        $row = $query->row();
        
        return $row->estado_arma;
    }    
    
    function nombreUnidad($unidad) {
        
        $query = $this->db->query("SELECT nombreunidad
                                   FROM unidades
                                   WHERE idunidad = ".$this->db->escape($unidad));
        
        $row = $query->row();
        
        return $row->nombreunidad;
    }
}

?>
