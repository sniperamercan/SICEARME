<?php

class consulta_actas_unidad_model extends CI_Model {
    
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
    function cantidadRegistros_alta($condicion) {
        $query = $this->db->query("SELECT DISTINCT (a.nro_acta)
                                   FROM actas_alta a
                                   INNER JOIN actas_alta_entrega_armamento aa ON a.nro_acta = aa.nro_acta
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db_actas_alta($ini, $param, $condicion, $order) {
        
        $result = array();

        $query = $this->db->query("SELECT DISTINCT (a.nro_acta), unidad_entrega, fecha_transaccion
                                   FROM actas_alta a
                                   INNER JOIN actas_alta_entrega_armamento aa ON a.nro_acta = aa.nro_acta
                                   WHERE ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_acta;
            $result[] = $row->unidad_entrega;
            $result[] = $row->fecha_transaccion;
        }
        
        return $result;
    } 
    
    //para paginado
    function cantidadRegistros_baja($condicion) {
        $query = $this->db->query("SELECT DISTINCT (a.nro_acta)
                                   FROM actas_baja a
                                   INNER JOIN actas_baja_devolucion_armamento aa ON a.nro_acta = aa.nro_acta
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db_actas_baja($ini, $param, $condicion, $order) {
        
        $result = array();

        $query = $this->db->query("SELECT DISTINCT (a.nro_acta), unidad_recibe, fecha_transaccion
                                   FROM actas_baja a
                                   INNER JOIN actas_baja_devolucion_armamento aa ON a.nro_acta = aa.nro_acta
                                   WHERE ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_acta;
            $result[] = $row->unidad_recibe;
            $result[] = $row->fecha_transaccion;
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
