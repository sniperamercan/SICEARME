<?php

class imprimir_ficha_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function verTipoSistema($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT c.tipo_arma, c.sistema
                                   FROM catalogos c
                                   INNER JOIN fichas f ON c.nro_interno = f.nro_interno_catalogo
                                   WHERE f.nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND f.marca         = ".$this->db->escape($marca)."
                                   AND f.calibre       = ".$this->db->escape($calibre)."
                                   AND f.modelo        = ".$this->db->escape($modelo));
        
        $row = $query->row();
        
        $retorno = array();
        
        $retorno[] = $row->tipo_arma;
        $retorno[] = $row->sistema;
        
        return $retorno;
    }
    
    function tieneAccesorios($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT * 
                                   FROM fichas_accesorios
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo));
        
        return $query->num_rows();
    }
    
    function tienePiezas($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT * 
                                   FROM fichas_piezas
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo));
        
        return $query->num_rows();
    }    
    
    function verAccesorios($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT nro_accesorio, tipo_accesorio, descripcion
                                   FROM fichas_accesorios
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_accesorio;
            $retorno[] = $row->tipo_accesorio;
            $retorno[] = $row->descripcion;
        }
        
        return $retorno;
    }
    
    function verPiezas($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT nro_pieza, tipo_pieza, descripcion
                                   FROM fichas_piezas
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_pieza;
            $retorno[] = $row->tipo_pieza;
            $retorno[] = $row->descripcion;
        }
        
        return $retorno;
    } 
}

?>
