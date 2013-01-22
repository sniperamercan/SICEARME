<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * clase para manejo de permisos
 * @author Sebastian Ituarte
 * @copyright 2013
 */

session_start();

Class Perms extends CI_Model {
	
	/**
	 * Sin parametros de entrada solo verifica que el usuario exista  
	 */
	
	function __construct(){	
            parent::__construct();	
            $this->load->database();
	}
	
	public function verificoUsuario(){
            
            if( !isset($_SESSION['usuario']) || !isset($_SESSION['usuario2']) ) {
                return false;
            }
            
            $valido = false;

            $usuario = $_SESSION['usuario'];
            $clave   = $_SESSION['usuario2'];

            $query = $this->db->query("SELECT clave
                                       FROM usuarios
                                       WHERE usuario=".$this->db->escape(base64_decode($usuario)));			

            if($query->num_rows() == 1){	
                $row = $query->row();					
                $clave2 = $row->clave;
                $clave2 = sha1($clave2);
                if(base64_decode($clave) == $clave2){
                    $valido = true;	
                }		
            }					

            return $valido;
	}
        
        //VERIFICO PERFILES DEL USUARIO
        
        //Administradores del sistema
        public function verificoPerfil1(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 1
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Usuario O.C.I
        public function verificoPerfil2(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 2
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Administrador O.C.I
        public function verificoPerfil3(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 3
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Usuario Abastecimiento
        public function verificoPerfil4(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 4
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Administrador Abastecimiento
        public function verificoPerfil5(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 5
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Usuario Taller de armamento
        public function verificoPerfil6(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 6
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Administrador Taller de armamento
        public function verificoPerfil7(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 7
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Usuario Reserva
        public function verificoPerfil8(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 8
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Usuario Almacen Taller de armamento
        public function verificoPerfil9(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 9
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }
        
        //Usuario Consultas
        public function verificoPerfil10(){
            
            if( !isset($_SESSION['usuario']) ) {
                return false;
            }
            
            $query = $this->db->query("SELECT *
                                       FROM permisos_usuario
                                       WHERE perfil = 10
                                       AND usuario = ".$this->db->escape(base64_decode($_SESSION['usuario'])));
            
            return $query->num_rows();
        }        
        
/*
	public static function verificoAdministrador($usuario){
		
		$registroAdmin = db::query("SELECT * 
	    							FROM administradores
	    							WHERE usuario=".db::quote($usuario));
		
		if(db::num_rows($registroAdmin) == 1){
			return true;
		}else{
			return false;
		}
		
	}	
	
	/**
	 * verificar acceso en modulos del sistema  
	 *//*
	public static function verificoPermisos($tipoPerm){
		
		$usuario = base64_decode($_SESSION['usuario']);
				
		$registro = db::query("SELECT permiso_tipo
							   FROM permisos p 
							   INNER JOIN usuarios u ON p.permiso_id = u.permiso_id
							   WHERE u.usuario=".db::quote($usuario));
		
		$reg = db::fetch_assoc($registro);
		
		$permUsu = $reg['permiso_tipo'];
				
		if($tipoPerm == PERM_ADMIN){//permiso para administrador acepta todo
			
			if($permUsu == PERM_ADMIN){				
				return true;				
			}else{
				return false;
			}
			
		}else if($tipoPerm == PERM_MANT){
			
			if($permUsu == PERM_MANT || $permUsu == PERM_ADMIN){
				return true;
			}else{
				return false;
			}
			
		}elseif($tipoPerm == PERM_USU){//permiso para usuario ver lo de consulta tambien
		
			if($permUsu == PERM_USU || $permUsu == PERM_MANT || $permUsu == PERM_ADMIN){				
				return true;			
			}else{
				return false;
			}
		
		}else{
			
			if($permUsu == PERM_CONSUL || $permUsu == PERM_USU || $permUsu == PERM_MANT || $permUsu == PERM_ADMIN){//permiso para consultas solo ve lo de consultas
				return true;
			}else{
				return false;
			}
			
		}
		
		
	}

	public static function getPermUsuario(){
		
		$usuario = base64_decode($_SESSION['usuario']);
		
		$registro = db::query("SELECT permiso_tipo
							   FROM permisos p 
							   INNER JOIN usuarios u ON p.permiso_id = u.permiso_id
							   WHERE u.usuario=".db::quote($usuario));
		
		$reg = db::fetch_assoc($registro);
		
		
		return $reg['permiso_tipo'];
		
	}	*/
}


?>