<?php

class listado_usuarios_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }   
    
    function listadoUsuarios() {
        
        $query = $this->db->query("SELECT usuario, nombre, apellido, estado
                                   FROM usuarios
                                   ORDER BY usuario");
        
        $usuarios = array();
        
        foreach($query->result() as $row) {
            $usuarios[]  = $row->usuario;
            $usuarios[]  = $row->nombre;
            $usuarios[]  = $row->apellido;
            $usuarios[]  = $row->estado;
        }
        
        return $usuarios;
    }
    
    function tienePermisos($usuario) {
        
        $query = $this->db->query("SELECT *
                                   FROM permisos_usuario
                                   WHERE usuario = ".$this->db->escape($usuario));
        
        return $query->num_rows();
    }
    
    function verPermisos($usuario) {
        
        $query = $this->db->query("SELECT pu.perfil, p.descripcion
                                   FROM permisos_usuario pu
                                   INNER JOIN permisos p ON pu.perfil = p.perfil
                                   WHERE pu.usuario = ".$this->db->escape($usuario));
        
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->perfil;
            $retorno[] = $row->descripcion;
        }
        
        return $retorno;
    }    
    
    
}

?>
