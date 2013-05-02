<?php

class busqueda_piezas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        
        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                   FROM ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($_SESSION['nro_orden']));
        
        $row = $query->row();        
        
        $query = $this->db->query("SELECT nro_pieza, tipo_pieza, descripcion
                                   FROM fichas_piezas
                                   WHERE nro_serie = ".$this->db->escape($row->nro_serie)."
                                   AND marca = ".$this->db->escape($row->marca)."
                                   AND calibre = ".$this->db->escape($row->calibre)."  
                                   AND modelo = ".$this->db->escape($row->modelo)."    
                                   ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                   FROM ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($_SESSION['nro_orden']));
        
        $row = $query->row();    
        
        $nro_serie = $row->nro_serie;
        $marca     = $row->marca;
        $calibre   = $row->calibre;
        $modelo    = $row->modelo;
                
        $query = $this->db->query("SELECT nro_pieza, tipo_pieza, descripcion
                                   FROM fichas_piezas
                                   WHERE nro_serie = ".$this->db->escape($row->nro_serie)."
                                   AND marca = ".$this->db->escape($row->marca)."
                                   AND calibre = ".$this->db->escape($row->calibre)."  
                                   AND modelo = ".$this->db->escape($row->modelo)."    
                                   ".$condicion."   
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_pieza;
            $result[] = $row->tipo_pieza;
            $result[] = $row->descripcion;
            $result[] = $nro_serie;
            $result[] = $marca;
            $result[] = $calibre;
            $result[] = $modelo;            
        }
        
        return $result;
    }     
    
}

?>