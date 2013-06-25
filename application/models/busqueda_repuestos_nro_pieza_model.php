<?php

class busqueda_repuestos_nro_pieza_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        
        $query = $this->db->query("SELECT f.nro_interno_catalogo
                                   FROM fichas f
                                   INNER JOIN ordenes_trabajo o ON f.nro_serie = o.nro_serie AND f.marca = o.marca AND f.calibre = o.calibre AND f.modelo = o.modelo
                                   WHERE o.nro_orden = ".$this->db->escape($_SESSION['nro_orden']));
        
        $row = $query->row();        
        
        $query = $this->db->query("SELECT * 
                                   FROM stock_repuestos_nro_pieza s
                                   INNER JOIN catalogos c ON s.nro_interno_catalogo = c.nro_interno
                                   WHERE c.nro_interno = ".$this->db->escape($row->nro_interno_catalogo)."
                                   ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $query = $this->db->query("SELECT f.nro_interno_catalogo
                                   FROM fichas f
                                   INNER JOIN ordenes_trabajo o ON f.nro_serie = o.nro_serie AND f.marca = o.marca AND f.calibre = o.calibre AND f.modelo = o.modelo
                                   WHERE o.nro_orden = ".$this->db->escape($_SESSION['nro_orden']));
        
        $row = $query->row();
        
        $result = array();        
                
        $query = $this->db->query("SELECT s.nro_pieza, s.nro_parte, s.nombre_parte, s.nro_interno_catalogo, c.tipo_arma, c.marca, c.calibre, c.modelo
                                   FROM stock_repuestos_nro_pieza s
                                   INNER JOIN catalogos c ON s.nro_interno_catalogo = c.nro_interno
                                   WHERE c.nro_interno = ".$this->db->escape($row->nro_interno_catalogo)."
                                   AND s.nombre_parte  = ".$this->db->escape($_SESSION['tipo_pieza'])."    
                                   ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_pieza;
            $result[] = $row->nro_parte;
            $result[] = $row->nombre_parte;
            $result[] = $row->nro_interno_catalogo;
            $result[] = $row->tipo_arma;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
        }
        
        return $result;
    }    
    
}

?>