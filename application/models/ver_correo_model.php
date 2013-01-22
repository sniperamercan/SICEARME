<?php

class ver_correo_model extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function cargoCorreo($id_correo) {
        
        $query = $this->db->query("SELECT usuario_envia, asunto, contenido
                                   FROM correos
                                   WHERE id_correo = ".$this->db->escape($id_correo));
        
        $row = $query->row();
        
        $correo = array();
        
        $correo[] = $row->usuario_envia;
        $correo[] = $row->asunto;
        $correo[] = $row->contenido;
        
        return $correo;
    }
    
    function mensajeLeido($id_correo) {
        
        $data_set = array(
            'leido' => 0
        );
        
        $data_where = array(
            'id_correo' => $id_correo
        );
        
        $this->db->update('correos', $data_set, $data_where);
    }
    
}

?>
