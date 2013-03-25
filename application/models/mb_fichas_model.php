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
    
    function existeHistorialFicha($nro_serie, $marca, $calibre, $modelo) {
        
        $cont = 0;
        
        $query = $this->db->query("SELECT *
                                   FROM actas_alta_entrega_armamento
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo));
        
        $cont = $query->num_rows();
        
        $query = $this->db->query("SELECT *
                                   FROM actas_alta_entrega_accesorios
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo));  
        
        $cont = $cont + $query->num_rows(); 
        
        $query = $this->db->query("SELECT *
                                   FROM actas_baja_devolucion_armamento
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo)); 
        
        $cont = $cont + $query->num_rows();
        
        $query = $this->db->query("SELECT *
                                   FROM actas_baja_devolucion_accesorios
                                   WHERE nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo));   
        
        $cont = $cont + $query->num_rows();
        
        return $cont;
    }
    
    function eliminarFicha($nro_serie, $marca, $calibre, $modelo) {
        
        $data_fichas = array(
            'nro_serie' => $nro_serie,
            'marca'     => $marca,
            'calibre'   => $calibre,
            'modelo'    => $modelo
        );
        
        $this->db->trans_start();
            $this->db->delete("stock_unidades_accesorios", $data_fichas);
            $this->db->delete("stock_unidades", $data_fichas);
            $this->db->delete("fichas_piezas", $data_fichas);
            $this->db->delete("fichas_accesorios", $data_fichas);
            $this->db->delete("fichas", $data_fichas);
        $this->db->trans_complete();  
    }
}

?>
