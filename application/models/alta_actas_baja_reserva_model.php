<?php

class alta_actas_baja_reserva_model extends CI_Model {
    
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
    
    function cargoNroSeries($unidad) {
        
        $query = $this->db->query("SELECT DISTINCT nro_serie
                                   FROM stock_unidades
                                   WHERE idunidad = ".$unidad."
                                   ORDER BY nro_serie");
        
        $nro_series = array();
        
        foreach($query->result() as $row) {
            $nro_series[] = $row->nro_serie;
        }
        
        return $nro_series;        
    }
    
    function cargoMarcas($unidad, $nro_serie) {
        
        $query = $this->db->query("SELECT DISTINCT marca
                                   FROM stock_unidades
                                   WHERE idunidad = ".$this->db->escape($unidad)."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."    
                                   ORDER BY marca");
        
        $marcas = array();
        
        foreach($query->result() as $row) {
            $marcas[] = $row->marca;
        }
        
        return $marcas;        
    }
    
    function cargoCalibres($unidad, $nro_serie, $marca) {
        
        $query = $this->db->query("SELECT DISTINCT calibre
                                   FROM stock_unidades
                                   WHERE idunidad = ".$this->db->escape($unidad)."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."
                                   AND marca      = ".$this->db->escape($marca)."
                                   ORDER BY calibre");
        
        $calibres = array();
        
        foreach($query->result() as $row) {
            $calibres[] = $row->calibre;
        }
        
        return $calibres;       
    }
    
    function cargoModelos($unidad, $nro_serie, $marca, $calibre) {
        
        $query = $this->db->query("SELECT DISTINCT modelo
                                   FROM stock_unidades
                                   WHERE idunidad = ".$this->db->escape($unidad)."
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
    
    function cargoNroSeriesAccesorios($unidad) {
        
        $query = $this->db->query("SELECT DISTINCT nro_serie
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad = ".$this->db->escape($unidad)."
                                   ORDER BY nro_serie");
        
        $nro_series = array();
        
        foreach($query->result() as $row) {
            $nro_series[] = $row->nro_serie;
        }
        
        return $nro_series;        
    }
    
    function cargoMarcasAccesorios($unidad, $nro_serie) {
        
        $query = $this->db->query("SELECT DISTINCT marca
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad = ".$this->db->escape($unidad)."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."    
                                   ORDER BY marca");
        
        $marcas = array();
        
        foreach($query->result() as $row) {
            $marcas[] = $row->marca;
        }
        
        return $marcas;        
    }
    
    function cargoCalibresAccesorios($unidad, $nro_serie, $marca) {

        $query = $this->db->query("SELECT DISTINCT calibre
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad = ".$this->db->escape($unidad)."
                                   AND nro_serie  = ".$this->db->escape($nro_serie)."
                                   AND marca      = ".$this->db->escape($marca)."
                                   ORDER BY calibre");
        
        $calibres = array();
        
        foreach($query->result() as $row) {
            $calibres[] = $row->calibre;
        }
        
        return $calibres;       
    }
    
    function cargoModelosAccesorios($unidad, $nro_serie, $marca, $calibre) {
        
        $query = $this->db->query("SELECT DISTINCT modelo
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad = ".$this->db->escape($unidad)."
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
    
    function cargoNroAccesorios($unidad, $nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT DISTINCT nro_accesorio
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad  = ".$this->db->escape($unidad)."
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
    
    function existeFicha($unidad, $nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT *
                                   FROM stock_unidades
                                   WHERE idunidad  = ".$this->db->escape($unidad)."
                                   AND nro_serie   = ".$this->db->escape($nro_serie)."
                                   AND marca       = ".$this->db->escape($marca)."
                                   AND calibre     = ".$this->db->escape($calibre)."
                                   AND modelo      = ".$this->db->escape($modelo));
        
        return $query->num_rows();
    }
    
    function existeAccesorio($unidad, $nro_serie, $marca, $calibre, $modelo, $nro_accesorio) {
        
        $query = $this->db->query("SELECT *
                                   FROM stock_unidades_accesorios
                                   WHERE idunidad    = ".$this->db->escape($unidad)."
                                   AND nro_serie     = ".$this->db->escape($nro_serie)."
                                   AND marca         = ".$this->db->escape($marca)."
                                   AND calibre       = ".$this->db->escape($calibre)."
                                   AND modelo        = ".$this->db->escape($modelo)."
                                   AND nro_accesorio = ".$this->db->escape($nro_accesorio));
        
        return $query->num_rows();        
        
    }
    
    function altaActa_db($fecha, $unidad_entrega, $representante_sma, $representante_unidad, $supervision, $observaciones) {
        
        $this->db->trans_start();
        
            $data_acta_baja = array(
                'fecha_transaccion'         => $fecha,
                'unidad_entrega'            => $unidad_entrega,
                'unidad_recibe'             => 98,
                'representante_sma'         => $representante_sma,
                'representante_unidad'      => $representante_unidad,
                'representante_supervision' => $supervision,
                'estado'                    => 0,
                'observaciones'             => $observaciones,
                'usuario_alta'              => base64_decode($_SESSION['usuario']),
                'usuario_edita'             => base64_decode($_SESSION['usuario'])
            );

            $this->db->insert('actas_baja', $data_acta_baja);

            $query = $this->db->query("SELECT last_insert_id() as nro_acta");

            $row = $query->row();

            $nro_acta = $row->nro_acta;        

            $data_db_logs = array(
                'tipo_movimiento' => 'insert',
                'tabla'           => 'actas_baja',
                'clave_tabla'     => 'nro_acta = '.$nro_acta,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);
            
            for($i=0;$i<count($_SESSION['fichas']);$i=$i+4) {

                $data_ficha = array(
                    'nro_acta'  => $nro_acta,
                    'nro_serie' => $_SESSION['fichas'][$i],
                    'marca'     => $_SESSION['fichas'][$i+1],
                    'calibre'   => $_SESSION['fichas'][$i+2],
                    'modelo'    => $_SESSION['fichas'][$i+3]
                );
                
                $this->db->insert("actas_baja_devolucion_armamento", $data_ficha);

                $data_stock_deposito = array(
                    'nro_serie' => $_SESSION['fichas'][$i],
                    'marca'     => $_SESSION['fichas'][$i+1],
                    'calibre'   => $_SESSION['fichas'][$i+2],
                    'modelo'    => $_SESSION['fichas'][$i+3]
                );
                
                $this->db->delete("stock_reserva", $data_stock_deposito);
                
                $data_db_logs = array(
                    'tipo_movimiento' => 'insert',
                    'tabla'           => 'actas_baja_devolucion_armamento',
                    'clave_tabla'     => 'nro_serie = '.$_SESSION['fichas'][$i]. ' && marca = '.$_SESSION['fichas'][$i+1].' && calibre = '.$_SESSION['fichas'][$i+2].' && modelo ='.$_SESSION['fichas'][$i+3].' && idunidad = '.$unidad_entrega,
                    'usuario'         => base64_decode($_SESSION['usuario'])
                );        

                $this->db->insert('db_logs', $data_db_logs);            
            }

            //si hay accesorios tambien se dan de alta
            for($i=0;$i<count($_SESSION['accesorios']);$i=$i+5) {

                $data_accesorio = array(
                    'nro_acta'      => $nro_acta,
                    'nro_serie'     => $_SESSION['accesorios'][$i],
                    'marca'         => $_SESSION['accesorios'][$i+1],
                    'calibre'       => $_SESSION['accesorios'][$i+2],
                    'modelo'        => $_SESSION['accesorios'][$i+3],
                    'nro_accesorio' => $_SESSION['accesorios'][$i+4]
                );

                $this->db->insert("actas_baja_devolucion_accesorios", $data_accesorio);

                $data_db_logs = array(
                    'tipo_movimiento' => 'insert',
                    'tabla'           => 'actas_baja_devolucion_accesorios',
                    'clave_tabla'     => 'nro_serie = '.$_SESSION['accesorios'][$i]. ' && marca = '.$_SESSION['accesorios'][$i+1].' && calibre = '.$_SESSION['accesorios'][$i+2].' && modelo ='.$_SESSION['accesorios'][$i+3].' && nro_accesorio = '.$_SESSION['accesorios'][$i+4].' && idunidad = '.$unidad_entrega,
                    'usuario'         => base64_decode($_SESSION['usuario'])
                );        

                $this->db->insert('db_logs', $data_db_logs);            
            } 

            /* ESTO SUCEDE CUANDO EL ESTADO CAMBIA DE 0 A 1
            //doy de alta las fichas del armamento a la unidad
            for($i=0;$i<count($_SESSION['fichas']);$i=$i+4) {

                $data_ficha = array(
                    'idunidad' => $unidad_recibe
                ); 

                $data_ficha_where = array(
                    'nro_serie' => $_SESSION['fichas'][$i],
                    'marca'     => $_SESSION['fichas'][$i+1],
                    'calibre'   => $_SESSION['fichas'][$i+2],
                    'modelo'    => $_SESSION['fichas'][$i+3]
                );

                $this->db->update("stock_unidades", $data_ficha, $data_ficha_where);

                $data_db_logs = array(
                    'tipo_movimiento' => 'update',
                    'tabla'           => 'stock_unidades',
                    'clave_tabla'     => 'nro_serie = '.$_SESSION['fichas'][$i]. ' && marca = '.$_SESSION['fichas'][$i+1].' && calibre = '.$_SESSION['fichas'][$i+2].' && modelo ='.$_SESSION['fichas'][$i+3].' && idunidad = '.$unidad_recibe,
                    'usuario'         => base64_decode($_SESSION['usuario'])
                );        

                $this->db->insert('db_logs', $data_db_logs);            
            }

            //si hay accesorios tambien se dan de alta
            for($i=0;$i<count($_SESSION['accesorios']);$i=$i+5) {

                $data_accesorio = array(
                    'idunidad' => $unidad_recibe
                ); 

                $data_accesorio_where = array(
                    'nro_serie'     => $_SESSION['accesorios'][$i],
                    'marca'         => $_SESSION['accesorios'][$i+1],
                    'calibre'       => $_SESSION['accesorios'][$i+2],
                    'modelo'        => $_SESSION['accesorios'][$i+3],
                    'nro_accesorio' => $_SESSION['accesorios'][$i+4]
                );

                $this->db->update("stock_unidades_accesorios", $data_accesorio, $data_accesorio_where);

                $data_db_logs = array(
                    'tipo_movimiento' => 'update',
                    'tabla'           => 'stock_unidades_accesorios',
                    'clave_tabla'     => 'nro_serie = '.$_SESSION['accesorios'][$i]. ' && marca = '.$_SESSION['accesorios'][$i+1].' && calibre = '.$_SESSION['accesorios'][$i+2].' && modelo ='.$_SESSION['accesorios'][$i+3].' && nro_accesorio = '.$_SESSION['accesorios'][$i+4].' && idunidad = '.$unidad_recibe,
                    'usuario'         => base64_decode($_SESSION['usuario'])
                );        

                $this->db->insert('db_logs', $data_db_logs);            
            }  */     
        
        $this->db->trans_complete();  
        
        return $nro_acta;
    }
    
}

?>
