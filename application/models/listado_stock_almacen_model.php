<?php

class listado_stock_almacen_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }   
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM stock_repuestos
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_parte, nombre_parte, precio_unitario, cantidad
                                   FROM stock_repuestos
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_parte;
            $result[] = $row->nombre_parte;
            $result[] = $row->precio;
            $result[] = $row->cantidad;
        }
        
        return $result;
    }
}

?>
