<?php

class imprimir_acta_baja_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }    
    
    function datosActa($nro_acta) {
        
        $query = $this->db->query("SELECT fecha_transaccion, unidad_entrega, representante_sma, representante_unidad, representante_supervision, observaciones
                                   FROM actas_baja
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $retorno = array();
        
        $row = $query->row();
        
        $retorno[] = $row->fecha_transaccion;
        $retorno[] = $row->unidad_entrega;
        $retorno[] = $row->representante_sma;
        $retorno[] = $row->representante_unidad;
        $retorno[] = $row->representante_supervision;
        $retorno[] = $row->observaciones;
        
        return $retorno;
    }
    
    function datosFichas($nro_acta) {
        
        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                   FROM actas_baja_devolucion_armamento
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
    
    function datosAccesorios($nro_acta) {
        
        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo, nro_accesorio
                                   FROM actas_baja_devolucion_accesorios
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
    
    function cargoNombreUnidad($idunidad) {
        
        $query = $this->db->query("SELECT nombreunidad
                                   FROM unidades
                                   WHERE idunidad = ".$this->db->escape($idunidad));
        
        $row = $query->row();
        
        return $row->nombreunidad;
    }
    
    function cargoNroSeries() {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT DISTINCT nro_serie
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
        
        $query = $this->db->query("SELECT DISTINCT marca
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
        
        $query = $this->db->query("SELECT DISTINCT calibre
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
        
        $query = $this->db->query("SELECT DISTINCT modelo
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
        
        $query = $this->db->query("SELECT DISTINCT nro_serie
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
        
        $query = $this->db->query("SELECT DISTINCT marca
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
        
        $query = $this->db->query("SELECT DISTINCT calibre
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
        
        $query = $this->db->query("SELECT DISTINCT modelo
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
        
        $query = $this->db->query("SELECT DISTINCT nro_accesorio
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
    
    function existeFicha($nro_serie, $marca, $calibre, $modelo) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT *
                                   FROM stock_unidades
                                   WHERE idunidad  = ".$this->db->escape('98')."
                                   AND nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo));
        
        return $query->num_rows();
    }
    
    function existeAccesorio($nro_serie, $marca, $calibre, $modelo, $nro_accesorio) {
        
        //idunidad = 98 - Deposito inicial
        
        $query = $this->db->query("SELECT *
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad    = ".$this->db->escape('98')."
                                   AND nro_serie     = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo)."
                                   AND nro_accesorio = ".$this->db->escape($nro_accesorio));
        
        return $query->num_rows();        
        
    }
    
}

?>
