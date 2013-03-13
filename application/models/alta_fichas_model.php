<?php

class alta_fichas_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoPaises() {
        
        $query = $this->db->query("SELECT nombre
                                   FROM paises
                                   ORDER BY nombre");
        
        $paises = array();
        
        foreach($query->result() as $row) {
            $paises[] = $row->nombre;
        }
        
        return $paises;
    }
    
    function agregarUsuario($usuario, $nombre, $apellido, $clave, $permisos) {
        
        $data_usuario = array(
            'usuario'  => $usuario,
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'clave'    => md5($clave)
        );
        
        $data_db_logs = array(
            'tipo_movimiento' => 'insert',
            'tabla'           => 'usuarios',
            'clave_tabla'     => 'usuario = '.$usuario,
            'usuario'         => base64_decode($_SESSION['usuario'])
        );        

        $this->db->trans_start();
            $this->db->insert('usuarios', $data_usuario);
            $this->db->insert('db_logs', $data_db_logs);
        $this->db->trans_complete();            
        
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
