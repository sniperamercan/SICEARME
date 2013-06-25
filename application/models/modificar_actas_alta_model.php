<?php

class modificar_actas_alta_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function verificoEstadoActa($nro_acta) {
        
        $query = $this->db->query("SELECT estado
                                   FROM actas_alta
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $row = $query->row();
        
        return $row->estado;
    }
    
    function datosActa($nro_acta) {
        
        $query = $this->db->query("SELECT fecha_transaccion, unidad_recibe, representante_sma, representante_unidad, representante_supervision, observaciones
                                   FROM actas_alta 
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $retorno = array();
        
        $row = $query->row();
        
        $retorno[] = $row->fecha_transaccion;
        $retorno[] = $row->unidad_recibe;
        $retorno[] = $row->representante_sma;
        $retorno[] = $row->representante_unidad;
        $retorno[] = $row->representante_supervision;
        $retorno[] = $row->observaciones;
        
        return $retorno;
    }
    
    function datosFichas($nro_acta) {
        
        $query = $this->db->query("SELECT nro_serie, marca, calibre, modelo
                                   FROM actas_alta_entrega_armamento
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
                                   FROM actas_alta_entrega_accesorios
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
    
    function modificarActa_db($nro_acta, $fecha, $unidad_recibe, $representante_sma, $representante_unidad, $supervision, $observaciones) {
        
        $this->db->trans_start();

            $data_acta_alta_where = array(
                'nro_acta' => $nro_acta
            );
        
            $this->db->delete('actas_alta_entrega_armamento', $data_acta_alta_where);
            $this->db->delete('actas_alta_entrega_accesorios', $data_acta_alta_where);            
        
            $data_acta_alta_set = array(
                'fecha_transaccion'         => $fecha,
                'unidad_entrega'            => 98,
                'unidad_recibe'             => $unidad_recibe,
                'representante_sma'         => $representante_sma,
                'representante_unidad'      => $representante_unidad,
                'representante_supervision' => $supervision,
                'observaciones'             => $observaciones,
                'usuario_edita'             => base64_decode($_SESSION['usuario'])
            );

            $this->db->update('actas_alta', $data_acta_alta_set, $data_acta_alta_where);

            $data_db_logs = array(
                'tipo_movimiento' => 'update',
                'tabla'           => 'actas_alta',
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

                $this->db->insert("actas_alta_entrega_armamento", $data_ficha);

                $data_db_logs = array(
                    'tipo_movimiento' => 'insert',
                    'tabla'           => 'actas_alta_entrega_armamento',
                    'clave_tabla'     => 'nro_serie = '.$_SESSION['fichas'][$i]. ' && marca = '.$_SESSION['fichas'][$i+1].' && calibre = '.$_SESSION['fichas'][$i+2].' && modelo ='.$_SESSION['fichas'][$i+3].' && idunidad = '.$unidad_recibe,
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

                $this->db->insert("actas_alta_entrega_accesorios", $data_accesorio);

                $data_db_logs = array(
                    'tipo_movimiento' => 'insert',
                    'tabla'           => 'actas_alta_entrega_accesorios',
                    'clave_tabla'     => 'nro_serie = '.$_SESSION['accesorios'][$i]. ' && marca = '.$_SESSION['accesorios'][$i+1].' && calibre = '.$_SESSION['accesorios'][$i+2].' && modelo ='.$_SESSION['accesorios'][$i+3].' && nro_accesorio = '.$_SESSION['accesorios'][$i+4].' && idunidad = '.$unidad_recibe,
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
