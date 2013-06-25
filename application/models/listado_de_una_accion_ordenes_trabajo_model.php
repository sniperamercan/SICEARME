<?php

class listado_de_una_accion_ordenes_trabajo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //para paginado
    function cantidadRegistros($nro_orden) {
        $query = $this->db->query("SELECT d.nro_accion, d.nro_orden, d.fecha, d.seccion, d.tipo_accion
                                   FROM detalles_ordenes_trabajo d
                                   INNER JOIN ordenes_trabajo o ON d.nro_orden = o.nro_orden
                                   WHERE (o.estado_orden_trabajo = 0 OR o.estado_orden_trabajo = 1)
                                   AND d.nro_orden =".$this->db->escape($nro_orden)."
                                   ORDER BY d.nro_accion");
        
        return $query->num_rows();
    }

    function consulta_db($nro_orden) {
        
        $result = array();

        $query = $this->db->query("SELECT d.nro_accion, d.fecha, d.seccion, d.tipo_accion
                                   FROM detalles_ordenes_trabajo d
                                   INNER JOIN ordenes_trabajo o ON d.nro_orden = o.nro_orden
                                   WHERE (o.estado_orden_trabajo = 0 OR o.estado_orden_trabajo = 1)
                                   AND d.nro_orden =".$this->db->escape($nro_orden)."
                                   ORDER BY d.nro_accion");
                                   //LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_accion;
            $result[] = $row->fecha;
            $result[] = $row->seccion;
            $result[] = $row->tipo_accion;
        }
        
        return $result;
    } 
    
    function cargoTipoAccion($nro_accion) {
        
        $query = $this->db->query("SELECT tipo_accion
                                   FROM detalles_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $row = $query->row();
        
        return $row->tipo_accion;
    }    
    
    function verInformacionAccionSimple($nro_accion) {
        
        $query = $this->db->query("SELECT nro_orden, fecha, seccion, detalles, tipo_accion
                                   FROM detalles_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $row = $query->row();
        
        $retorno = array();
        
        $retorno[] = $row->nro_orden;
        $retorno[] = $row->fecha;
        $retorno[] = $row->seccion;
        $retorno[] = $row->detalles;
        $retorno[] = $row->tipo_accion;
                
        return $retorno;
    }
    
    function verInformacionAccionSecundaria($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT nro_cambio, nro_parte, nombre_parte, cantidad
                                   FROM cambio_piezas_no_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)." 
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_cambio;
            $retorno[] = $row->nro_parte;
            $retorno[] = $row->nombre_parte;
            $retorno[] = $row->cantidad;
        }
                
        return $retorno;        
    }
    
    function verInformacionAccionAsociada($nro_orden, $nro_accion) {
        
        $query = $this->db->query("SELECT nro_cambio, nro_pieza_anterior, nro_pieza_nueva, nro_parte, nombre_parte
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)." 
                                   AND nro_accion = ".$this->db->escape($nro_accion));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_cambio;
            $retorno[] = $row->nro_pieza_anterior;
            $retorno[] = $row->nro_pieza_nueva;
            $retorno[] = $row->nro_parte;
            $retorno[] = $row->nombre_parte;
        }
                
        return $retorno; 
        
    }
    
    function obtenerCantidadActual($nro_parte, $nombre_parte) {
        
        $query = $this->db->query("SELECT cantidad
                                   FROM stock_repuestos
                                   WHERE nro_parte = ".$this->db->escape($nro_parte)." 
                                   AND nombre_parte = ".$this->db->escape($nombre_parte));
        
        $row = $query->row();
        
        return $row->cantidad;
    }
    
    function piezasSecundariasUsadasAccion($nro_accion) {
        
        $query = $this->db->query("SELECT nro_parte, nombre_parte, cantidad
                                   FROM cambio_piezas_no_asociadas_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $datos = array();
        
        foreach($query->result() as $row) {
            
            $datos[] = $row->nro_parte;
            $datos[] = $row->nombre_parte;
            $datos[] = $row->cantidad;
        }
        
        return $datos;
    }
   
    function hayPiezaCambio($nro_accion) {
        
        $query = $this->db->query("SELECT f.*
                                   FROM fichas_piezas f
                                   INNER JOIN ordenes_trabajo o ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   INNER JOIN detalles_ordenes_trabajo d ON o.nro_orden = d.nro_orden
                                   WHERE d.nro_accion = ".$this->db->escape($nro_accion)."
                                   AND f.nro_pieza IN (SELECT c.nro_pieza_nueva 
                                                       FROM cambio_piezas_asociadas_ordenes_trabajo c 
                                                       WHERE c.nro_pieza_nueva = f.nro_pieza 
                                                       AND c.nro_accion = ".$this->db->escape($nro_accion).")");
        
        return $query->num_rows();
    }
    
    function hayAcciones($nro_accion) {
        
        $query = $this->db->query("SELECT *
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        return $query->num_rows();
    }
    
    function obtenerDatosArma($nro_accion) {
        
        $query = $this->db->query("SELECT o.nro_serie, o.marca, o.calibre, o.modelo
                                   FROM ordenes_trabajo o
                                   INNER JOIN detalles_ordenes_trabajo d ON o.nro_orden = d.nro_orden
                                   WHERE d.nro_accion = ".$this->db->escape($nro_accion));
        
        $datos_arma = array();
        
        $row = $query->row();
        
        $datos_arma[] = $row->nro_serie;
        $datos_arma[] = $row->marca;
        $datos_arma[] = $row->calibre;
        $datos_arma[] = $row->modelo;
        
        return $datos_arma;
    }
    
    function obtenerNroCatalogo($nro_accion) {
        
        $query = $this->db->query("SELECT f.nro_interno_catalogo
                                   FROM fichas f
                                   INNER JOIN ordenes_trabajo o ON o.nro_serie = f.nro_serie AND o.marca = f.marca AND o.calibre = f.calibre AND o.modelo = f.modelo
                                   INNER JOIN detalles_ordenes_trabajo d ON o.nro_orden = d.nro_orden
                                   WHERE d.nro_accion = ".$this->db->escape($nro_accion));
        
        $row = $query->row();
        
        return $row->nro_interno_catalogo;        
        
    }
    
    function piezasAsociadasUsadasAccion($nro_accion) {
        
        $query = $this->db->query("SELECT nro_pieza_anterior, nro_pieza_nueva, nro_parte, nombre_parte
                                   FROM cambio_piezas_asociadas_ordenes_trabajo
                                   WHERE nro_accion = ".$this->db->escape($nro_accion));
        
        $datos = array();
        
        foreach($query->result() as $row) {
            
            $datos[] = $row->nro_pieza_anterior;
            $datos[] = $row->nro_pieza_nueva;
            $datos[] = $row->nro_parte;
            $datos[] = $row->nombre_parte;
        }
        
        return $datos;
    }    
}

?>
