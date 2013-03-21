<?php

class alta_actas_alta_model extends CI_Model {
    
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
    
    function cargoNroSeries() {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT nro_serie
                                   FROM stock_unidades
                                   WHERE idunidad = ".$this->db->escape('98')."
                                   ORDER BY nro_serie");
        
        $nro_series = array();
        
        foreach($query->result() as $row) {
            $nro_series[] = $row->nro_serie;
        }
        
        return $nro_series;        
    }
    
    function cargoMarcas($nro_serie) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT marca
                                   FROM stock_unidades
                                   WHERE idunidad = ".$this->db->escape('98')."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."    
                                   ORDER BY marca");
        
        $marcas = array();
        
        foreach($query->result() as $row) {
            $marcas[] = $row->marca;
        }
        
        return $marcas;        
    }
    
    function cargoCalibres($nro_serie, $marca) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT calibre
                                   FROM stock_unidades
                                   WHERE idunidad = ".$this->db->escape('98')."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."
                                   AND marca      = ".$this->db->escape($marca)."
                                   ORDER BY calibre");
        
        $calibres = array();
        
        foreach($query->result() as $row) {
            $calibres[] = $row->calibre;
        }
        
        return $calibres;       
    }
    
    function cargoModelos($nro_serie, $marca, $calibre) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT modelo
                                   FROM stock_unidades
                                   WHERE idunidad = ".$this->db->escape('98')."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."
                                   AND marca      = ".$this->db->escape($marca)."
                                   AND calibre    = ".$this->db->escape($calibre)."
                                   ORDER BY modelo");
        
        $modelos = array();
        
        foreach($query->result() as $row) {
            $modelos[] = $row->modelo;
        }
        
        return $modelos;        
    }    
    
    function cargoNroSeriesAccesorios() {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT nro_serie
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad = ".$this->db->escape('98')."
                                   ORDER BY nro_serie");
        
        $nro_series = array();
        
        foreach($query->result() as $row) {
            $nro_series[] = $row->nro_serie;
        }
        
        return $nro_series;        
    }
    
    function cargoMarcasAccesorios($nro_serie) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT marca
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad = ".$this->db->escape('98')."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."    
                                   ORDER BY marca");
        
        $marcas = array();
        
        foreach($query->result() as $row) {
            $marcas[] = $row->marca;
        }
        
        return $marcas;        
    }
    
    function cargoCalibresAccesorios($nro_serie, $marca) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT calibre
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad = ".$this->db->escape('98')."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."
                                   AND marca      = ".$this->db->escape($marca)."
                                   ORDER BY calibre");
        
        $calibres = array();
        
        foreach($query->result() as $row) {
            $calibres[] = $row->calibre;
        }
        
        return $calibres;       
    }
    
    function cargoModelosAccesorios($nro_serie, $marca, $calibre) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT modelo
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad = ".$this->db->escape('98')."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."
                                   AND marca      = ".$this->db->escape($marca)."
                                   AND calibre    = ".$this->db->escape($calibre)."
                                   ORDER BY modelo");
        
        $modelos = array();
        
        foreach($query->result() as $row) {
            $modelos[] = $row->modelo;
        }
        
        return $modelos;        
    }     
    
    function cargoNroAccesorios($nro_serie, $marca, $calibre, $modelo) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT nro_accesorio
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad  = ".$this->db->escape('98')."
                                   AND nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo)."
                                   ORDER BY modelo");
        
        $nro_accesorios = array();
        
        foreach($query->result() as $row) {
            $nro_accesorios[] = $row->nro_accesorio;
        }
        
        return $nro_accesorios;        
    }     
    
    function agregarUsuario($usuario, $nombre, $apellido, $clave, $permisos) {
        
        $data_usuario = array(
            'usuario'  => $usuario,
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'clave'    => md5($clave)
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'usuarios',
            'clave_tabla'     => 'usuario = '.$usuario,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->trans_start();
            $this->db->insert('usuarios', $data_usuario);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();            
        
        foreach($permisos as $val) {
            
            $data_permisos = array(
                'usuario'  => $usuario,
                'perfil'   => $val
            );
            
            $this->db->insert('permisos_usuario', $data_permisos);
        }
    
    }
    
}

?>
