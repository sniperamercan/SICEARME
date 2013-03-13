<?php

class mb_compras_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }   
    
    function listadoUsuarios() {
        
        $query = $this->db->query("SELECT usuario, nombre, apellido
                                   FROM usuarios
                                   ORDER BY usuario");
        
        $usuarios = array();
        
        foreach($query->result() as $row) {
            $usuarios[]  = $row->usuario;
            $usuarios[]   = $row->nombre;
            $usuarios[] = $row->apellido;
        }
        
        return $usuarios;
    }
    
    
}

?>
