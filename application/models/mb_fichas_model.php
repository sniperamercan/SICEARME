<?php

class mb_fichas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM fichas
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo, nro_interno_compra, nro_interno_catalogo
                                   FROM fichas
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_serie;
            $result[] = $row->marca;
            $result[] = $row->calibre;
            $result[] = $row->modelo;
            $result[] = $row->nro_interno_compra;
            $result[] = $row->nro_interno_catalogo;
        }
        
        return $result;
    } 
    
    function tieneCatalogos($nro_interno) {
        
        $query = $this->db->query("SELECT * 
                                   FROM  compras_catalogos
                                   WHERE nro_interno_compra = ".$this->db->escape($nro_interno));
        
        return $query->num_rows();
    }
    
    function verCatalogos($nro_interno) {
        
        $query = $this->db->query("SELECT cc.nro_interno_catalogo, c.tipo_arma, c.marca, c.calibre, c.modelo, c.sistema, c.empresa, c.pais_origen
                                   FROM compras_catalogos cc
                                   INNER JOIN catalogos c ON cc.nro_interno_catalogo = c.nro_interno
                                   WHERE cc.nro_interno_compra = ".$this->db->escape($nro_interno));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_interno_catalogo;
            $retorno[] = $row->tipo_arma;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
            $retorno[] = $row->sistema;
            $retorno[] = $row->empresa;
            $retorno[] = $row->pais_origen;
        }
        
        return $retorno;
    }
}

?>
