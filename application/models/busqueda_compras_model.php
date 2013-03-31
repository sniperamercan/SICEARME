<?php

class busqueda_compras_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT cc.nro_interno_compra, cc.nro_interno_catalogo, ca.tipo_arma, ca.marca, ca.calibre, ca.modelo
                                   FROM compras_catalogos cc
                                   INNER JOIN catalogos ca ON cc.nro_interno_catalogo = ca.nro_interno
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT cc.nro_interno_compra, cc.nro_interno_catalogo, ca.tipo_arma, ca.marca, ca.calibre, ca.modelo
                                   FROM compras_catalogos cc
                                   INNER JOIN catalogos ca ON cc.nro_interno_catalogo = ca.nro_interno
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_interno_compra;
            $result[] = $row->nro_interno_catalogo;
            $result[] = $row->tipo_arma;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
        }
        
        return $result;
    }    
    
}

?>