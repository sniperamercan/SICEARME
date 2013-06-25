<?php

class listado_inventario_reserva_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM stock_unidades u
                                   WHERE idunidad = 96
                                   ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT u.nro_serie, u.marca, u.calibre, u.modelo, r.deposito
                                   FROM stock_unidades u
                                   LEFT OUTER JOIN stock_reserva r ON u.nro_serie = r.nro_serie AND u.marca = r.marca AND u.calibre = r.calibre AND u.modelo = r.modelo
                                   WHERE idunidad = 96
                                   ".$condicion."
                                   ORDER BY ".$order);
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->deposito;
        }
        
        return $result;
    } 
}

?>
