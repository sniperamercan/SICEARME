<?php

class listado_cambios_piezas_asociadas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT *
                                   FROM cambio_piezas_asociadas_ordenes_trabajo c
                                   INNER JOIN ordenes_trabajo o ON c.nro_orden = o.nro_orden
                                   WHERE (estado_orden_trabajo = 0 OR estado_orden_trabajo = 1)
                                   ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT c.nro_orden, o.nro_serie, o.marca, o.calibre, o.modelo, c.nro_pieza_anterior, c.nro_pieza_nueva, c.nro_parte, c.nombre_parte, c.nro_cambio
                                   FROM cambio_piezas_asociadas_ordenes_trabajo c
                                   INNER JOIN ordenes_trabajo o ON c.nro_orden = o.nro_orden
                                   WHERE (estado_orden_trabajo = 0 OR estado_orden_trabajo = 1)
                                   ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_orden;
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nro_pieza_anterior;
            $result[] = $row->nro_pieza_nueva;
            $result[] = $row->nro_parte;
            $result[] = $row->nombre_parte;
            $result[] = $row->nro_cambio;
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
