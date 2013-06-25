<?php

class mb_usuarios_model extends CI_Model {
 
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
    
    function obtenerEstado($usuario) {
        
        $query = $this->db->query("SELECT estado
                                   FROM usuarios
                                   WHERE usuario = ".$this->db->escape($usuario));
        
        $row = $query->row();
        
        return $row->estado;
    }
    
    function registroIngresos($usuario) {
        
        $query = $this->db->query("SELECT *
                                   FROM logs_ingresos
                                   WHERE logusuario = ".$this->db->escape($usuario));
        
        return $query->num_rows();
    }
    
    function cambiarEstado($usuario, $estado) {
        
        $data_usuario_set = array(
            'estado' => $estado
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
    }
    
    function vaciarClave($usuario) {
        
        $data_usuario_set = array(
            'clave' => md5('sicearme')
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
    }
    
    function eliminarUsuario($usuario) {
        
        $data_usuario_where = array(
            'usuario' => $usuario
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'delete',
            'tabla'           => 'usuarios',
            'clave_tabla'     => 'usuario = '.$usuario,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        
        
        $this->db->trans_start();
            $this->db->delete('permisos_usuario', $data_usuario_where);
            $this->db->delete('usuarios', $data_usuario_where);
            $this->db->insert('db_logs', $data_db_logs); 
        $this->db->trans_complete();         
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
