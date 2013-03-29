<?php

class redactar_correo_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoUsuarios() {
        
        $query = $this->db->query("SELECT usuario
                                   FROM usuarios");
        
        $usuarios = array();
        
        foreach($query->result() as $row) {
            $usuarios[] = $row->usuario;
        }
        
        return $usuarios;
    }
    
    function ingresarDato($destinatario, $asunto, $contenido) {
        
        $data_correo = array(
            'usuario_envia'  => base64_decode($_SESSION['usuario']),
            'usuario_recibe' => $destinatario,
            'asunto'         => $asunto,
            'contenido'      => $contenido,
            'leido'          => 1
        );
        
        $this->db->insert('correos', $data_correo);
    }
    
    function obtengoUsuarioEnvia($id_correo) {
        
        $query = $this->db->query("SELECT usuario_envia, asunto 
                                   FROM correos
                                   WHERE id_correo = ".$this->db->escape($id_correo));
        
        $row = $query->row();
        
        $datos_correo = array(
            'usuario_envia' => $row->usuario_envia,
            'asunto'        => $row->asunto
        );
        
        return $datos_correo;
    }
    
}

?>
