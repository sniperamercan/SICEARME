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
    
    function consultoEmpresas() {
        
        $query = $this->db->query("SELECT rut
                                   FROM empresas
                                   ORDER BY rut");
        
        $empresas = array();
        
        foreach($query->result() as $row) {
            $empresas[] = $row->rut;
        }
        
        return $empresas;
    }    
    
    function hayDinamige($rut) {
        
        $query = $this->db->query("SELECT *
                                   FROM dinamige
                                   WHERE rut = ".$this->db->escape($rut));
        
        return $query->num_rows();
    }
    
    function hayBps($rut) {
        
        $query = $this->db->query("SELECT *
                                   FROM bps
                                   WHERE rut = ".$this->db->escape($rut));
        
        return $query->num_rows();
    }
    
    function hayDgi($rut) {
        
         $query = $this->db->query("SELECT *
                                   FROM dgi
                                   WHERE rut = ".$this->db->escape($rut));
        
        return $query->num_rows();   }
    
    function hayBancoSeguros($rut) {
        
        $query = $this->db->query("SELECT *
                                   FROM banco_seguros
                                   WHERE rut = ".$this->db->escape($rut));
        
        return $query->num_rows();
    }
    
    function hayCertificadoConfianza($rut) {
        
        $query = $this->db->query("SELECT *
                                   FROM certificado_confianza
                                   WHERE rut = ".$this->db->escape($rut));
        
        return $query->num_rows();
    }    
    
    function fechaValidezDinamige($rut) {
        
        $query = $this->db->query("SELECT nro_certificado, fecha_validez
                                   FROM dinamige
                                   WHERE rut = ".$this->db->escape($rut));        
     
        $retorno = array();
        
        foreach($query->result() as $row) {
            $retorno[] = $row->nro_certificado;
            $retorno[] = $row->fecha_validez;
        }
        
        return $retorno;
    }
    
    function fechaValidezBps($rut) {
        
        $query = $this->db->query("SELECT nro_certificado, fecha_validez
                                   FROM bps
                                   WHERE rut = ".$this->db->escape($rut));   
        
        $row = $query->row();
        
        $retorno = array();
        $retorno[] = $row->nro_certificado;
        $retorno[] = $row->fecha_validez; 
        
        return $retorno;
    }
    
    function fechaValidezDgi($rut) {
        
        $query = $this->db->query("SELECT nro_certificado, fecha_validez
                                   FROM dgi
                                   WHERE rut = ".$this->db->escape($rut));   
        
        $row = $query->row();
        
        $retorno = array();
        $retorno[] = $row->nro_certificado;
        $retorno[] = $row->fecha_validez;   
        
        return $retorno;
    }
    
    function fechaValidezBancoSeguros($rut) {
        
        $query = $this->db->query("SELECT nro_certificado, fecha_validez
                                   FROM banco_seguros
                                   WHERE rut = ".$this->db->escape($rut));   
        
        $row = $query->row();
        
        $retorno = array();
        $retorno[] = $row->nro_certificado;
        $retorno[] = $row->fecha_validez;  
        
        return $retorno;
    }
    
    function fechaValidezCertificadoConfianza($rut) {
        
        $query = $this->db->query("SELECT nro_certificado, fecha_validez
                                   FROM certificado_confianza
                                   WHERE rut = ".$this->db->escape($rut));   
        
        $row = $query->row();
        
        $retorno = array();
        $retorno[] = $row->nro_certificado;
        $retorno[] = $row->fecha_validez;  
        
        return $retorno;
    }    
    
    
    function enviarAlerta($rut, $certificado, $nro_certificado, $fecha) {
        
        $contenido = "
            La empresa con numero de rut - ".$rut.", tiene el siguiente certificado - ".$certificado.",
            con numero de certificado - ".$nro_certificado." vencido en la fecha ".$fecha.".
        ";
        
        
        $data = array(
            'usuario_envia'  => 'Administracion',
            'usuario_recibe' => base64_decode($_SESSION['usuario']),
            'asunto'         => 'Alerta',
            'contenido'      => $contenido,
            'leido'          => 1
        );
        
        $this->db->insert('correos', $data);
        
    }
    
}

?>
