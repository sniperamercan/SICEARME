<?php

class listado_ordenes_trabajo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM ordenes_trabajo
                                   WHERE (estado_orden_trabajo = 0 OR estado_orden_trabajo = 1)
                                   ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_orden, fecha, nro_serie, marca, calibre, modelo, nombreunidad, estado_orden_trabajo
                                   FROM ordenes_trabajo o
                                   INNER JOIN unidades u ON u.idunidad = o.idunidad
                                   WHERE (estado_orden_trabajo = 0 OR estado_orden_trabajo = 1)
                                   ".$condicion."
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
            $result[] = $row->estado_orden_trabajo;
        }
        
        return $result;
    } 
    
    function verObservaciones($nro_orden) {
        
        $query = $this->db->query("SELECT observaciones
                                   FROM ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden));
        
        $row = $query->row();
        
        return $row->observaciones;
    }

    function hayAcciones($nro_orden) {
        
        $query = $this->db->query("SELECT *
                                   FROM detalles_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden));
        
        return $query->num_rows();
    }
}

?>
