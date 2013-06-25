<?php

class listado_mutaciones_armamentos_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM mutaciones_armamentos_logs
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_mutacion, nro_orden, nro_serie, marca, calibre, modelo, nro_serie_nuevo, fecha_accion, seccion
                                   FROM mutaciones_armamentos_logs
                                   WHERE ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_mutacion;
            $result[] = $row->nro_orden;
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nro_serie_nuevo;
            $result[] = $row->fecha_accion;
            $result[] = $row->seccion;
        }
        
        return $result;
    } 
}

?>
