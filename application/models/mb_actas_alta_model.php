<?php

class mb_actas_alta_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function verEstadoActa($nro_acta) {
        
        $query = $this->db->query("SELECT estado
                                   FROM actas_alta
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $row = $query->row();
        
        return $row->estado;
    }
    
    function eliminarActa($nro_acta) {
        
        $data_acta_where = array(
            'nro_acta' => $nro_acta
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'delete',
            'tabla'           => 'actas_alta',
            'clave_tabla'     => 'nro_acta = '.$nro_acta,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );          
        
        $this->db->trans_start();
            $this->db->insert('db_logs', $data_db_logs); 
            $this->db->delete("actas_alta_entrega_accesorios", $data_acta_where);
            $this->db->delete("actas_alta_entrega_armamento", $data_acta_where);
            $this->db->delete("actas_alta", $data_acta_where);
        $this->db->trans_complete(); 
        
    }
    
    function obtenerFichasActas($nro_acta) {
        
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
    
    function obtenerAccesoriosActas($nro_acta) {
        
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
    
    function obterUnidadRecibe($nro_acta) {
        
        $query = $this->db->query("SELECT unidad_recibe
                                   FROM actas_alta
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $row = $query->row();
        
        return $row->unidad_recibe;
    }
    
    function activarActa($nro_acta, $datos_fichas, $datos_accesorios, $unidad_recibe) {
        
        for($i=0;$i<count($datos_fichas);$i=$i+4) {

            $data_ficha = array(
                'idunidad' => $unidad_recibe
            ); 

            $data_ficha_where = array(
                'nro_serie' => $datos_fichas[$i],
                'marca'     => $datos_fichas[$i+1],
                'calibre'   => $datos_fichas[$i+2],
                'modelo'    => $datos_fichas[$i+3]
            );

            $this->db->update("stock_unidades", $data_ficha, $data_ficha_where);

            $data_db_logs = array(
                'tipo_movimiento' => 'update',
                'tabla'           => 'stock_unidades',
                'clave_tabla'     => 'nro_serie = '.$datos_fichas[$i]. ' && marca = '.$datos_fichas[$i+1].' && calibre = '.$datos_fichas[$i+2].' && modelo ='.$datos_fichas[$i+3].' && idunidad = '.$unidad_recibe,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);            
        }

        //si hay accesorios tambien se dan de alta
        for($i=0;$i<count($datos_accesorios);$i=$i+5) {

            $data_accesorio = array(
                'idunidad' => $unidad_recibe
            ); 

            $data_accesorio_where = array(
                'nro_serie'     => $datos_accesorios[$i],
                'marca'         => $datos_accesorios[$i+1],
                'calibre'       => $datos_accesorios[$i+2],
                'modelo'        => $datos_accesorios[$i+3],
                'nro_accesorio' => $datos_accesorios[$i+4]
            );

            $this->db->update("stock_unidades_accesorios", $data_accesorio, $data_accesorio_where);

            $data_db_logs = array(
                'tipo_movimiento' => 'update',
                'tabla'           => 'stock_unidades_accesorios',
                'clave_tabla'     => 'nro_serie = '.$datos_accesorios[$i]. ' && marca = '.$datos_accesorios[$i+1].' && calibre = '.$datos_accesorios[$i+2].' && modelo ='.$datos_accesorios[$i+3].' && nro_accesorio = '.$datos_accesorios[$i+4].' && idunidad = '.$unidad_recibe,
                'usuario'         => base64_decode($_SESSION['usuario'])
            );        

            $this->db->insert('db_logs', $data_db_logs);  
        }
        
        //actualizo el estado del acta
        $data_acta_estado_set = array(
            'estado' => 1
        );
        
        $data_acta_estado_where = array(
            'nro_acta' => $nro_acta
        );
        
        $this->db->update("actas_alta", $data_acta_estado_set, $data_acta_estado_where);
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'actas_alta',
            'clave_tabla'     => 'nro_serie = '.$nro_acta,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);        
        
    }
    
    function verObservaciones($nro_acta) {
        
        $query = $this->db->query("SELECT observaciones
                                   FROM actas_alta
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        $row = $query->row();
        
        return $row->observaciones;
    }
    
    //para paginado
    function cantidadRegistros($condicion){
        $query = $this->db->query("SELECT * 
                                   FROM actas_alta
                                   WHERE ".$condicion);
        
        return $query->num_rows();
    }

    function consulta_db($ini, $param, $condicion, $order){
        
        $result = array();

        $query = $this->db->query("SELECT nro_acta, fecha_transaccion, unidad_entrega, unidad_recibe, representante_sma, representante_unidad, representante_supervision, estado
                                   FROM actas_alta
                                   WHERE ".$condicion."
                                   ORDER BY ".$order."
                                   LIMIT ".$ini.",".$param);
        
        foreach($query->result() as $row){
            $result[] = $row->nro_acta;
            $result[] = $row->fecha_transaccion;
            $result[] = $row->unidad_entrega;
            $result[] = $row->unidad_recibe;
            $result[] = $row->representante_sma;
            $result[] = $row->representante_unidad;
            $result[] = $row->representante_supervision;
            $result[] = $row->estado;
        }
        
        return $result;
    } 
    
    function nombreUnidad($idunidad) {
        
        $query = $this->db->query("SELECT nombreunidad
                                   FROM unidades
                                   WHERE idunidad = ".$this->db->escape($idunidad));
        
        $row = $query->row();
        
        return $row->nombreunidad;
    }
    
    function tieneFichas($nro_acta) {
        
        $query = $this->db->query("SELECT * 
                                   FROM  actas_alta_entrega_armamento
                                   WHERE nro_acta = ".$this->db->escape($nro_acta));
        
        return $query->num_rows();
    }
    
    function verFichas($nro_acta) {
        
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
    
    function verAccesorios($nro_acta) {
        
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
}

?>
