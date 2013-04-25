<?php

class alta_ordenes_trabajo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function cargoUnidades() {
        
        $query = $this->db->query("SELECT idunidad, nombreunidad
                                   FROM unidades
                                   ORDER BY nombreunidad");
        
        $unidades = array();
        
        foreach($query->result() as $row) {
            $unidades[] = $row->idunidad;
            $unidades[] = $row->nombreunidad;
        }
        
        return $unidades;
    }
    
    //unidad 99 Taller de armamento
    function cargoNroSeries() {
       
        $query = $this->db->query("SELECT DISTINCT nro_serie
                                   FROM stock_unidades
                                   WHERE idunidad = 99
                                   ORDER BY nro_serie");
        
        $nro_series = array();
        
        foreach($query->result() as $row) {
            $nro_series[] = $row->nro_serie;
        }
        
        return $nro_series;        
    }    
    
    function cargoMarcas($nro_serie) {
        
        $query = $this->db->query("SELECT DISTINCT marca
                                   FROM stock_unidades
                                   WHERE nro_serie  = ".$this->db->escape($nro_serie)."    
                                   ORDER BY marca");
        
        $marcas = array();
        
        foreach($query->result() as $row) {
            $marcas[] = $row->marca;
        }
        
        return $marcas;        
    }
    
    function cargoCalibres($nro_serie, $marca) {
        
        $query = $this->db->query("SELECT DISTINCT calibre
                                   FROM stock_unidades
                                   WHERE nro_serie  = ".$this->db->escape($nro_serie)."
                                   AND marca      = ".$this->db->escape($marca)."
                                   ORDER BY calibre");
        
        $calibres = array();
        
        foreach($query->result() as $row) {
            $calibres[] = $row->calibre;
        }
        
        return $calibres;       
    }
    
    function cargoModelos($nro_serie, $marca, $calibre) {
        
        $query = $this->db->query("SELECT DISTINCT modelo
                                   FROM stock_unidades
                                   WHERE nro_serie  = ".$this->db->escape($nro_serie)."
                                   AND marca      = ".$this->db->escape($marca)."
                                   AND calibre    = ".$this->db->escape($calibre)."
                                   ORDER BY modelo");
        
        $modelos = array();
        
        foreach($query->result() as $row) {
            $modelos[] = $row->modelo;
        }
        
        return $modelos;        
    }  
    
    function cargoDatos($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT c.tipo_arma, c.sistema
                                   FROM catalogos c
                                   INNER JOIN fichas f ON f.nro_interno_catalogo = c.nro_interno
                                   WHERE f.nro_serie = ".$this->db->escape($nro_serie)."
                                   AND f.marca = ".$this->db->escape($marca)."
                                   AND f.calibre = ".$this->db->escape($calibre)."
                                   AND f.modelo = ".$this->db->escape($modelo));
        
        $datos = array();
        
        if($query->num_rows() > 0) {
            $row = $query->row();

            $datos[] = $row->tipo_arma;
            $datos[] = $row->sistema;
        }else {
            $datos[] = "";
            $datos[] = "";
        }
        
        return $datos;
    }
    
    function altaOrdenTrabajo($fecha, $unidad, $nro_serie, $marca, $calibre, $modelo, $observaciones) {
        
        $data_ordenes_trabajo = array(
            'fecha'                => $fecha,
            'nro_serie'            => $nro_serie,
            'marca'                => $marca,
            'calibre'              => $calibre,
            'modelo'               => $modelo,
            'observaciones'        => $observaciones,
            'idunidad'             => $unidad,
            'estado_arma'          => 0,
            'estado_orden_trabajo' => 0,
            'usuario'              => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->insert('ordenes_trabajo', $data_ordenes_trabajo);
        
        $query = $this->db->query("SELECT last_insert_id() as nro_orden");
        
        $row = $query->row();
        
        $nro_orden = $row->nro_orden;
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'ordenes_trabajo',
            'clave_tabla'     => 'nro_orden = '.$nro_orden,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
        
        return $nro_orden;
    }
}

?>
