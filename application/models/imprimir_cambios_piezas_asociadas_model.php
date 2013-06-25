<?php

class imprimir_cambios_piezas_asociadas_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function cantidadActual($nro_cambio) {
        
        $query = $this->db->query("SELECT c.nro_orden, o.nro_serie, o.marca, o.calibre, o.modelo, c.nro_pieza_anterior, c.nro_pieza_nueva, c.nro_parte, c.nombre_parte, c.nro_cambio
                                   FROM cambio_piezas_asociadas_ordenes_trabajo c
                                   INNER JOIN ordenes_trabajo o ON c.nro_orden = o.nro_orden
                                   WHERE (estado_orden_trabajo = 0 OR estado_orden_trabajo = 1)
                                   AND c.nro_cambio = ".$this->db->escape($nro_cambio));
        
        $row = $query->row();
        
        return $row->cantidad;
    }
    
    function datosCambio($nro_cambio) {
        
        $query = $this->db->query("SELECT c.nro_orden, o.nro_serie, o.marca, o.calibre, o.modelo, c.nro_pieza_anterior, c.nro_pieza_nueva, c.nro_parte, c.nombre_parte, c.nro_cambio
                                   FROM cambio_piezas_asociadas_ordenes_trabajo c
                                   INNER JOIN ordenes_trabajo o ON c.nro_orden = o.nro_orden
                                   WHERE (estado_orden_trabajo = 0 OR estado_orden_trabajo = 1)
                                   AND c.nro_cambio = ".$this->db->escape($nro_cambio));
        
        $result = array();
        
        $row = $query->row();
        
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
        
        return $result;
    }
}

?>
