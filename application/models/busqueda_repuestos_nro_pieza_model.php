<?php

class busqueda_repuestos_nro_pieza_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        
        $query = $this->db->query("SELECT marca, calibre, modelo
                                   FROM ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($_SESSION['nro_orden']));
        
        $row = $query->row();        
        
        $query = $this->db->query("SELECT * 
                                   FROM stock_repuestos_nro_pieza s
                                   INNER JOIN catalogos c ON s.nro_interno_catalogo = c.nro_interno
                                   WHERE c.marca = ".$this->db->escape($row->marca)."
                                   AND c.calibre = ".$this->db->escape($row->calibre)."  
                                   AND c.modelo = ".$this->db->escape($row->modelo)."    
                                   ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $query = $this->db->query("SELECT marca, calibre, modelo
                                   FROM ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($_SESSION['nro_orden']));
        
        $row = $query->row();
        
        $result = array();        
                
        $query = $this->db->query("SELECT s.nro_pieza, s.nro_parte, s.nombre_parte, s.nro_interno_catalogo, c.tipo_arma, c.marca, c.calibre, c.modelo
                                   FROM stock_repuestos_nro_pieza s
                                   INNER JOIN catalogos c ON s.nro_interno_catalogo = c.nro_interno
                                   WHERE c.marca = ".$this->db->escape($row->marca)."
                                   AND c.calibre = ".$this->db->escape($row->calibre)."  
                                   AND c.modelo = ".$this->db->escape($row->modelo)."    
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