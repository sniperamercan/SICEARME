<?php

class alta_empresa_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function existeEmpresa($empresa) {
        
        $query = $this->db->query("SELECT *
                                   FROM empresas
                                   WHERE empresa = ".$this->db->escape($empresa));
        
        return $query->num_rows();
    }
    
    function altaEmpresa($empresa) {
        
        $data_empresa = array(
            'empresa' => $empresa
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'empresas',
            'clave_tabla'     => 'empresa = '.$empresa,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->insert('empresas', $data_empresa);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();    
    }
}

?>
