<?php

class alta_deposito_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeDeposito($deposito) {
        
        $query = $this->db->query("SELECT *
                                   FROM depositos_reserva
                                   WHERE deposito = ".$this->db->escape($deposito));
        
        return $query->num_rows();
    }
    
    function altaDeposito($deposito) {
        
        $data_empresa = array(
            'deposito' => $deposito
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'depositos_reserva',
            'clave_tabla'     => 'deposito = '.$deposito,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('depositos_reserva', $data_empresa);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
