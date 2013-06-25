<?php

class consulta_stock_total_armas_resumido_model extends CI_Model {
    
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
    
    function conjutoUnidades() {
        
        $query = $this->db->query("SELECT DISTINCT idunidad
                                   FROM stock_unidades
                                   ORDER BY idunidad");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->idunidad;
        }
        
        return $result;
    }
    
    //para paginado
    function cantidadRegistros($condicion) {
        $query = $this->db->query("SELECT s.marca, s.calibre, s.modelo, c.tipo_arma, c.sistema, COUNT(*) AS cantidad
                                   FROM stock_unidades s
                                   INNER JOIN fichas f ON s.nro_serie = f.nro_serie AND s.marca = f.marca AND s.calibre = f.calibre AND s.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion."
                                   GROUP BY s.marca, s.calibre, s.modelo");
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order) {
        
        $result = array();

        $query = $this->db->query("SELECT s.marca, s.calibre, s.modelo, c.tipo_arma, c.sistema, COUNT(*) AS cantidad
                                   FROM stock_unidades s
                                   INNER JOIN fichas f ON s.nro_serie = f.nro_serie AND s.marca = f.marca AND s.calibre = f.calibre AND s.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion."
                                   GROUP BY s.marca, s.calibre, s.modelo");
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->tipo_arma;
            $result[] = $row->sistema;
            $result[] = $row->cantidad;
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
