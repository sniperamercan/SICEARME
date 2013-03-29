<?php

class listado_actas_alta_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function verEstadoActa($nro_acta) {
        
        $query = $this->db->query("SELECT estado
                                   FROM actas_alta
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $row = $query->row();
        
        return $row->estado;
    }
    
    function obtenerFichasActas($nro_acta) {
        
        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                   FROM actas_alta_entrega_armamento
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_serie;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
        }
        
        return $retorno;
    }
    
    function obtenerAccesoriosActas($nro_acta) {
        
        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo, nro_accesorio
                                   FROM actas_alta_entrega_accesorios
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_serie;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
            $retorno[] = $row->nro_accesorio;
        }
        
        return $retorno;
    }    
    
    function obterUnidadRecibe($nro_acta) {
        
        $query = $this->db->query("SELECT unidad_recibe
                                   FROM actas_alta
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $row = $query->row();
        
        return $row->unidad_recibe;
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
    
    function tieneFichas($nro_acta) {
        
        $query = $this->db->query("SELECT * 
                                   FROM  actas_alta_entrega_armamento
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        return $query->num_rows();
    }
    
    function verFichas($nro_acta) {
        
        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                   FROM actas_alta_entrega_armamento
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_serie;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
        }
        
        return $retorno;
    }
    
    function verAccesorios($nro_acta) {
        
        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo, nro_accesorio
                                   FROM actas_alta_entrega_accesorios
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_serie;
            $retorno[] = $row->marca;
            $retorno[] = $row->calibre;
            $retorno[] = $row->modelo;
            $retorno[] = $row->nro_accesorio;
        }
        
        return $retorno;
    }    
}


?>
