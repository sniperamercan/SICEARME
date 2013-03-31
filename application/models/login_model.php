<?php

class login_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
 
    function verificoUsuario_db($usuario, $clave) {        
        $query = $this->db->query("SELECT * 
                                   FROM usuarios 
                                   WHERE usuario = ".$this->db->escape($usuario)." 
                                   AND clave = ".$this->db->escape($clave));
     
        return $query->num_rows();        
    }
    
    function verificoEstado($usuario) {
        
        $query = $this->db->query("SELECT estado
                                   FROM usuarios
                                   WHERE usuario = ".$this->db->escape($usuario));
        
        $row = $query->row();
        
        return $row->estado;
    }
    
    function ingresoLog($usuario) {
        
        $this->db->trans_start();
        
            $data = array(
                'logusuario' => $usuario,
                'logfecha'   => date("Y-m-d"),
                'loghora'    => date("G:i"),
                'logip'      => $_SERVER['REMOTE_ADDR']
            );

            $this->db->insert('logs_ingresos', $data); 

            $query = $this->db->query("SELECT * 
                                       FROM usuarios_en_linea 
                                       WHERE usuario = ".$this->db->escape($usuario));

            if(!$query->num_rows()) {            
                $data = array(
                    'usuario' => $usuario
                );            
                $this->db->insert("usuarios_en_linea", $data);
            }
        
        $this->db->trans_complete(); 
        
        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return 0;
        }else{
            return 1;
        } 
        
    }
    
    function verificarUsuarioAlertas($usuario) {
        
        $query = $this->db->query("SELECT *
                                   FROM usuarios_admin_correo
                                   WHERE usuario = ".$this->db->escape($usuario));
        
        return $query->num_rows();
    }
    
}

?>
