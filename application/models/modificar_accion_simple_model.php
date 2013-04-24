<?php

class modificar_accion_simple_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
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
    
    function cargoDatos($nro_accion) {
        
        $query = $this->db->query("SELECT d.nro_orden, d.fecha, d.seccion, d.detalles, o.nro_serie, o.marca, o.calibre, o.modelo, c.tipo_arma
                                   FROM detalles_ordenes_trabajo d
                                   INNER JOIN ordenes_trabajo o ON d.nro_orden = o.nro_orden
                                   INNER JOIN fichas f ON o.nro_serie = f.nro_serie 
                                   AND o.marca = f.marca
                                   AND o.calibre = f.calibre
                                   AND o.modelo = f.modelo
                                   INNER JOIN catalogos c ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE d.nro_accion = ".$this->db->escape($nro_accion));
        
        $row = $query->row();
        
        $datos = array();
        $datos[] = $row->nro_orden;
        $datos[] = $row->fecha;
        $datos[] = $row->seccion;
        $datos[] = $row->detalles;
        $datos[] = $row->nro_serie;
        $datos[] = $row->marca;
        $datos[] = $row->calibre;
        $datos[] = $row->modelo;
        $datos[] = $row->tipo_arma;
        
        return $datos;
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
