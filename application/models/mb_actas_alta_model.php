<?php

class mb_actas_alta_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function verObservaciones($nro_acta) {
        
        $query = $this->db->query("SELECT observaciones
                                   FROM actas_alta
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $row = $query->row();
        
        return $row->observaciones;
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM actas_alta
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_acta, fecha_transaccion, unidad_entrega, unidad_recibe, representante_sma, representante_unidad, representante_supervision, estado
                                   FROM actas_alta
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_acta;
            $result[] = $row->fecha_transaccion;
            $result[] = $row->unidad_entrega;
            $result[] = $row->unidad_recibe;
            $result[] = $row->representante_sma;
            $result[] = $row->representante_unidad;
            $result[] = $row->representante_supervision;
            $result[] = $row->estado;
        }
        
        return $result;
    } 
    
    function nombreUnidad($idunidad) {
        
        $query = $this->db->query("SELECT nombreunidad
                                   FROM unidades
                                   WHERE idunidad = ".$this->db->escape($idunidad));
        
        $row = $query->row();
        
        return $row->nombreunidad;
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
