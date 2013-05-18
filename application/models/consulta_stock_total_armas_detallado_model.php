<?php

class consulta_stock_total_armas_detallado_model extends CI_Model {
    
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
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT *
                                   FROM stock_unidades
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                   FROM stock_unidades
                                   WHERE ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
        }
        
        return $result;
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
