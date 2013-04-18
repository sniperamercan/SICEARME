<?php

class busqueda_ordenes_trabajo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM ordenes_trabajo
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT o.nro_orden, o.fecha, o.nro_serie, o.marca, o.calibre, o.modelo, u.nombreunidad
                                   FROM ordenes_trabajo o
                                   INNER JOIN unidades u ON o.idunidad = u.idunidad 
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_orden;
            $result[] = $row->fecha;
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nombreunidad;
        }
        
        return $result;
    }    
    
}

?>