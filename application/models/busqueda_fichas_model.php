<?php

class busqueda_fichas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT *
                                   FROM stock_unidades s
                                   INNER JOIN fichas f ON s.nro_serie = f.nro_serie AND s.marca = f.marca AND s.calibre = f.calibre AND s.modelo = f.modelo 
                                   WHERE ".$condicion." 
                                   AND idunidad = ".$this->db->escape($_SESSION['unidad']));
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT s.nro_serie, f.nro_interno_compra, f.nro_interno_catalogo, s.marca, s.calibre, s.modelo
                                   FROM stock_unidades s
                                   INNER JOIN fichas f ON s.nro_serie = f.nro_serie AND s.marca = f.marca AND s.calibre = f.calibre AND s.modelo = f.modelo 
                                   WHERE ".$condicion."
                                   AND idunidad = ".$this->db->escape($_SESSION['unidad'])."   
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            if($this->verificoOrdenTrabajo($row->nro_serie, $row->marca, $row->calibre, $row->modelo)) {
                $result[] = $row->nro_serie;
                $result[] = $row->nro_interno_compra;
                $result[] = $row->nro_interno_catalogo;
                $result[] = $row->marca;
                $result[] = $row->calibre;
                $result[] = $row->modelo;
            }
        }
        
        return $result;
    }  
    
    function verificoOrdenTrabajo($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT *
                                   FROM ordenes_trabajo
                                   WHERE nro_serie = ".$this->db->escape($nro_serie)."
                                   AND marca   = ".$this->db->escape($marca)."
                                   AND calibre = ".$this->db->escape($calibre)." 
                                   AND modelo  = ".$this->db->escape($modelo)."
                                   AND estado_orden_trabajo = 0");
        
        return $query->num_rows();
    }    
    
}

?>