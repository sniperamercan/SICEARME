<?php

class accion_ordenes_trabajo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function cargoNroOrdenes() {
        
        $query = $this->db->query("SELECT nro_orden
                                   FROM ordenes_trabajo
                                   WHERE estado_orden_trabajo = 0
                                   ORDER BY nro_orden");
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_orden;
        }
        
        return $retorno;
    }
    
    function cargoSecciones() {
        
        $query = $this->db->query("SELECT seccion
                                   FROM secciones
                                   ORDER BY seccion");
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->seccion;
        }
        
        return $retorno;
    }   
    
    function cargoDatosArma($nro_orden) {
        
        $query = $this->db->query("SELECT o.nro_serie, o.marca, o.calibre, o.modelo, c.tipo_arma
                                   FROM ordenes_trabajo o
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie 
                                   AND o.marca = f.marca
                                   AND o.calibre = f.calibre
                                   AND o.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE nro_orden = ".$this->db->escape($nro_orden));
        
        $row = $query->row();
        
        $datos = array();
        $datos[] = $row->nro_serie;
        $datos[] = $row->marca;
        $datos[] = $row->calibre;
        $datos[] = $row->modelo;
        $datos[] = $row->tipo_arma;
        
        return $datos;
    }
    
    function cargoAcciones($nro_orden) {
        
        $query = $this->db->query("SELECT nro_accion, fecha, seccion, tipo_accion
                                   FROM detalles_ordenes_trabajo
                                   WHERE nro_orden = ".$this->db->escape($nro_orden)."
                                   ORDER BY nro_accion");
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_accion;
            $retorno[] = $row->fecha;
            $retorno[] = $row->seccion;
            $retorno[] = $row->tipo_accion;
        }
        
        return $retorno;
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
    
    function altaAccionSimple($fecha, $nro_orden, $seccion, $observaciones, $tipo_accion) {
        
        $data_accion_simple = array(
            'nro_orden'   => $nro_orden,
            'fecha'       => $fecha,
            'seccion'     => $seccion,
            'detalles'    => $observaciones,
            'tipo_accion' => $tipo_accion //0 - accion simple 1- accion piezas secundarias 2- accion piezas asociadas.
        );
        
        $this->db->insert('detalles_ordenes_trabajo', $data_accion_simple);
        
        $query = $this->db->query("SELECT last_insert_id() as nro_accion");

        $row = $query->row();

        $nro_accion = $row->nro_accion;           
 
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'detalles_ordenes_trabajo',
            'clave_tabla'     => 'nro_orden = '.$nro_orden,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
            
        $this->db->insert('db_logs', $data_db_logs);
        
        return $nro_accion;
    }
}

?>
