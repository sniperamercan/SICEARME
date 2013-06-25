<?php

class consulta_movimientos_repuestos_model extends CI_Model {
    
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
    
    //para paginado
    function cantidadRegistros($condicion) {
        $query = $this->db->query("SELECT DISTINCT (d.fecha), d.seccion, SUM(cp.cantidad) AS cantidad
                                   FROM detalles_ordenes_trabajo d
                                   INNER JOIN ordenes_trabajo o ON d.nro_orden = o.nro_orden
                                   INNER JOIN cambio_piezas_no_asociadas_ordenes_trabajo cp ON o.nro_orden = cp.nro_orden AND cp.nro_accion = d.nro_accion
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order) {
        
        $result = array();

        $query = $this->db->query("SELECT DISTINCT (d.fecha), d.seccion, SUM(cp.cantidad) AS cantidad
                                   FROM detalles_ordenes_trabajo d
                                   INNER JOIN ordenes_trabajo o ON d.nro_orden = o.nro_orden
                                   INNER JOIN cambio_piezas_no_asociadas_ordenes_trabajo cp ON o.nro_orden = cp.nro_orden AND cp.nro_accion = d.nro_accion
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->fecha;
            $result[] = $row->seccion;
            $result[] = $row->cantidad;
        }
        
        return $result;
    } 
    
    function cantidadRegistros_asociadas($condicion) {
        $query = $this->db->query("SELECT DISTINCT d.fecha, d.seccion
                                   FROM detalles_ordenes_trabajo d
                                   INNER JOIN ordenes_trabajo o ON d.nro_orden = o.nro_orden
                                   INNER JOIN cambio_piezas_asociadas_ordenes_trabajo cp ON o.nro_orden = cp.nro_orden AND cp.nro_accion = d.nro_accion
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }    
    
    function consulta_db_asociadas($ini, $param, $condicion, $order) {
        
        $result = array();

        $query = $this->db->query("SELECT DISTINCT d.fecha, d.seccion
                                   FROM detalles_ordenes_trabajo d
                                   INNER JOIN ordenes_trabajo o ON d.nro_orden = o.nro_orden
                                   INNER JOIN cambio_piezas_asociadas_ordenes_trabajo cp ON o.nro_orden = cp.nro_orden AND cp.nro_accion = d.nro_accion
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->fecha;
            $result[] = $row->seccion;
            $result[] = 1;
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
