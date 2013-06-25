<?php

class alta_inventario_reserva_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function hayDepositos() {
        
        $query = $this->db->query("SELECT deposito 
                                   FROM depositos_reserva
                                   ORDER BY deposito");
        
        return $query->num_rows();
    }
    
    function cargoDepositos() {
        
        $query = $this->db->query("SELECT deposito 
                                   FROM depositos_reserva
                                   ORDER BY deposito");
        
        $result = array();
        
        foreach($query->result() as $row) {
            $result[] = $row->deposito;
        }
        
        return $result;
    }
    
    function hayStockReserva($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT *
                                   FROM stock_reserva s
                                   WHERE s.nro_serie = ".$this->db->escape($nro_serie)."
                                   AND s.marca = ".$this->db->escape($marca)."
                                   AND s.calibre = ".$this->db->escape($calibre)."
                                   AND s.modelo = ".$this->db->escape($modelo));  
        
        return $query->num_rows();
    }
    
    function cargoStockReserva($nro_serie, $marca, $calibre, $modelo) {
        
        $query = $this->db->query("SELECT s.deposito
                                   FROM stock_reserva s
                                   WHERE s.nro_serie = ".$this->db->escape($nro_serie)."
                                   AND s.marca = ".$this->db->escape($marca)."
                                   AND s.calibre = ".$this->db->escape($calibre)."
                                   AND s.modelo = ".$this->db->escape($modelo));  
        
        $row = $query->row();
        
        return $row->deposito;
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
    
    function altaInventario($nro_serie, $marca, $calibre, $modelo, $deposito_nuevo) {
        
        $data_inventario = array(
            'nro_serie'  => $nro_serie,
            'marca'      => $marca,
            'calibre'    => $calibre,
            'modelo'     => $modelo,
            'deposito'   => $deposito_nuevo
        );
        
        $this->db->insert('stock_reserva', $data_inventario);
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'stock_reserva',
            'clave_tabla'     => 'nro_serie = '.$nro_serie.', marca = '.$marca.'calibre = '.$calibre.'modelo = '.$modelo.'deposito = '.$deposito_nuevo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
    }
    
    function modificarInventario($nro_serie, $marca, $calibre, $modelo, $deposito_nuevo) {
        
        $data_inventario_where = array(
            'nro_serie'  => $nro_serie,
            'marca'      => $marca,
            'calibre'    => $calibre,
            'modelo'     => $modelo
        );
        
        $data_inventario_set = array(
            'deposito' => $deposito_nuevo
        );
        
        $this->db->update('stock_reserva', $data_inventario_set, $data_inventario_where);
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'stock_reserva',
            'clave_tabla'     => 'nro_serie = '.$nro_serie.', marca = '.$marca.'calibre = '.$calibre.'modelo = '.$modelo.'deposito = '.$deposito_nuevo,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->insert('db_logs', $data_db_logs);
    }    
}

?>
