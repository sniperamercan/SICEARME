<?php

class busqueda_accesorios_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT *
                                   FROM stock_unidades_accesorios s
                                   INNER JOIN fichas f ON s.nro_serie = f.nro_serie AND s.marca = f.marca AND s.calibre = f.calibre AND s.modelo = f.modelo 
                                   WHERE ".$condicion." 
                                   AND idunidad = ".$this->db->escape(98));
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT s.nro_serie, f.nro_interno_compra, f.nro_interno_catalogo, s.marca, s.calibre, s.modelo, s.nro_accesorio
                                   FROM stock_unidades_accesorios s
                                   INNER JOIN fichas f ON s.nro_serie = f.nro_serie AND s.marca = f.marca AND s.calibre = f.calibre AND s.modelo = f.modelo 
                                   WHERE ".$condicion."
                                   AND idunidad = ".$this->db->escape(98)."    
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_serie;
            $result[] = $row->nro_interno_compra;
            $result[] = $row->nro_interno_catalogo;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nro_accesorio;
        }
        
        return $result;
    }     
    
}

?>