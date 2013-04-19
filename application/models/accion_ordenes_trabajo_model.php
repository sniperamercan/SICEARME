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
    
    function altaAccionSimple($fecha, $nro_orden, $seccion, $observaciones) {
        
        $data_accion_simple = array(
            'nro_orden'   => $nro_orden,
            'fecha'       => $fecha,
            'seccion'     => $seccion,
            'detalles'    => $observaciones,
            'tipo_accion' => 0 //0 - accion simple 1- accion piezas secundarias 2- accion piezas asociadas.
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'detalles_ordenes_trabajo',
            'clave_tabla'     => 'nro_orden = '.$nro_orden,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('detalles_ordenes_trabajo', $data_accion_simple);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();         
    }
    

    
}

?>
