<?php

class consulta_logs_ingresos_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cantidadRegistros() {
        
        $query = $this->db->query("SELECT *
                                   FROM logs_ingresos");
        
        return $query->num_rows();
    }
    
    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT logusuario, logfecha, loghora, logip
                                   FROM logs_ingresos
                                   WHERE ".$condicion."
                                   ORDER BY logfecha DESC, loghora DESC
                                   LIMIT 30");
                                         
        /*
        $query = $this->db->query("SELECT tipo_materia_prima, cantidad
                                   FROM stock_materia_prima
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        */
        
        foreach($query->result() as $row){
            $result[] = $row->logusuario;
            $result[] = $row->logfecha;
            $result[] = $row->loghora;
            $result[] = $row->logip;
        }
        
        return $result;
    }    
    
}

?>
