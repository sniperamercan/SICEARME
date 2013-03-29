<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
 * Clase para manejo centralizado de errores 
 */

class mensajes {
    
    //error cuando la transaccion no se pudo completar
    function errorSolicitud() {
        return "ERROR: Hubo un error en su solicitud.";
    }
    
    function errorVacio($var) {
        return "ERROR: El campo ".$var." no puede estar vacío. Por favor verifique e intente nuevamente.";        
    }
    
    function errorExiste($var) {
        return "ERROR: El campo ".$var." que desea ingresar ya se encuentra en la base de datos.";
    }
    
    function errorNoExiste($var) {
        return "ERROR: El campo ".$var." no existe en la base de datos.";
    }    
    
    function errorExisteRegistro($var) {
        return "ERROR: El registro ".$var." que desea ingresar ya existe.";
    }    
    
    function errorNumerico($var) {
        return "ERROR: El campo ".$var." debe ser numérico. Por favor verifique e intente nuevamente.";
    }    
    
    function errorCantidad($var, $cantidad = 0) {
        return "ERROR: La cantidad de ".$var." debe ser mayor a ".$cantidad.". Por favor verifique e intente nuevamente.";
    }    
    
    function errorCatalogoUnico($nro_catalogo) {
        return "ERROR: El catálogo con el tipo de arma, marca, modelo y calibre que desea ingresar, ya existe. Por favor verifique el catálogo nº ".$nro_catalogo.".";
    }
    
    function mailIncorrecto() {
        return "ERROR: El mail que desea ingresar tiene un formato incorrecto. Por favor verifique e intente nuevamente.";
    }
    
    function fechasIncorrectas() {
        return "ERROR: La fecha de ingreso no puede ser mayor o igual a la fecha de baja. Por favor verifique e intente nuevamente.";
    }
    
    function sinPerfilSeleccionado() {
        return "ERROR: No seleccionó ningún perfil para el usuario, por lo menos debe seleccionar uno. Por favor verifique e intente nuevamente.";
    }
    
    function errorCatalogoExiste() {
        return "ERROR: El número de catálogo seleccionado ya existe en la compra. Por favor verifique.";
    }
    
    function errorAccesorioExiste() {
        return "ERROR: El número de accesorio seleccionado ya existe en listado de la ficha. Por favor verifique.";
    }
    
    function errorFichaExiste() {
        return "ERROR: La ficha seleccionada ya existe en listado del armamento a entregar. Por favor verifique.";
    }    
    
    function errorPiezaExiste() {
        return "ERROR: El número de pieza seleccionada ya existe en listado de la ficha. Por favor verifique.";
    }    
    
    //para modulo de usuarios
    function estadoUsuarioCambiado($usuario) {
        return "El estado del usuario ".$usuario." fue modificado con éxito.";
    }      
    
    function vaciarClave($usuario) {
        return "La clave del usuario ".$usuario." fue modificada a 'sicearme' con éxito.";
    }
    
    function sinPermisos() {
        return "ERROR: Usted no tiene los permisos necesarios para acceder a esta pantalla. Por favor comuniquese con un administrador.";
    }
    
    function errorUsuario() {
        return "ERROR: Acceso no permitido. Por favor comuniquese con un administrador.";
    }
    
    function errorUsuarioVacio() {
        return "ERROR: El campo usuario no puede estar vacío. Por favor verifique e intente nuevamente.";
    }
    
    function errorClaveVacia() {
        return "ERROR: La clave no puede estar vacía. Por favor verifique e intente nuevamente.";
    }
    
    function errorDatosIncorrectos() {
        return "ERROR: Alguno de los datos no fue ingresado correctamente. Por favor verifique e intente nuevamente.";
    }
    
    function errorUsuarioInactivo() {
        return "ERROR: Su usuario esta inactivo por lo que no puede acceder al sistema. Por favor comuniquese con un administrador.";
    }
    
    function usuarioEliminado($usuario) {
        return "El usuario ".$usuario." fue eliminado con éxito del sistema.";
    }
    
    
}

?>
