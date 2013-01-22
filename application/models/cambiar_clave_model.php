<?php

class cambiar_clave_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function verificoClave($clave_antigua) {
        
        $clave = md5($clave_antigua);
        
        $query = $this->db->query("SELECT *
                                   FROM usuarios
                                   WHERE usuario = ".$this->db->escape(base64_decode($_SESSION['usuario']))."
                                   AND clave = ".$this->db->escape($clave));
        
        return $query->num_rows();
    }
    
    function cambiarClave($clave) {
        
        $data_set = array(
            'clave' => md5($clave)
        );
        
        $data_where = array(
            'usuario' => base64_decode($_SESSION['usuario'])
        );
        
        $this->db->update('usuarios', $data_set, $data_where);
    }
    
}

?>
