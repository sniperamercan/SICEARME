<?php

class modificar_usuarios_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function existeUsuario($usuario) {
        
        $query = $this->db->query("SELECT *
                                   FROM usuarios
                                   WHERE usuario = ".$this->db->escape($usuario));
        
        return $query->num_rows();
    }
    
    function cargoPermisos() {
        
        $query = $this->db->query("SELECT * 
                                   FROM permisos
                                   ORDER BY perfil");
        
        $permisos = array();
        
        foreach($query->result() as $row) {
            $permisos[] = $row->perfil;
            $permisos[] = $row->descripcion;
        }
        
        return $permisos;
        
    }
    
    function obtenerDatosUsuario($usuario) {
        
        $query = $this->db->query("SELECT nombre, apellido 
                                   FROM usuarios
                                   WHERE usuario = ".$this->db->escape($usuario));
        
        $datos_usuarios = array();
        
        $row = $query->row();
        
        $datos_usuarios[] = $row->nombre;
        $datos_usuarios[] = $row->apellido;
        
        return $datos_usuarios; 
    }
    
    function permisosUsuario($usuario) {
        
        $query = $this->db->query("SELECT perfil
                                   FROM permisos_usuario
                                   WHERE usuario = ".$this->db->escape($usuario)."
                                   ORDER BY perfil");
        
        $permisos = array();
        
        foreach($query->result() as $row) {
            $permisos[] = $row->perfil;
        }
        
        return $permisos;        
    }
    
    function modificarUsuario($usuario, $nombre, $apellido, $permisos) {
        
        $data_usuario_set = array(
            'nombre'   => $nombre,
            'apellido' => $apellido
        );
        
        $data_usuario_where = array(
            'usuario' => $usuario
        );        
        
        $data_db_logs = array(
            'tipo_movimiento' => 'update',
            'tabla'           => 'usuarios',
            'clave_tabla'     => 'usuario = '.$usuario,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->trans_start();
            $this->db->update('usuarios', $data_usuario_set, $data_usuario_where);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();            
        
        //elimino todos los permisos y los vuelo a ingresar
        $this->db->delete('permisos_usuario', $data_usuario_where);
        
        foreach($permisos as $val) {
            
            $data_permisos = array(
                'usuario'  => $usuario,
                'perfil'   => $val
            );
            
            $this->db->insert('permisos_usuario', $data_permisos);
        }
    }
}

?>
