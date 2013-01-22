<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
 * Clase para manejo centralizado de errores 
 */

class mensajes {
    
    function sinPermisos() {
        return "USTED NO TIENE PERMISO PARA ACCEDER A ESTA PANTALLA, SE GENERO UN REGISTRO DE ACCESO NO PERMITIDO";
    }
    
    function errorUsuario() {
        return "ACCESO NO PERMITIDO, SE ALMACENO UN REGISTRO";
    }
    
    function errorUsuarioVacio() {
        return "El usuario no puede ser vacio";
    }
    
    function errorClaveVacia() {
        return "La clave no puede ser vacia";
    }
    
    function errorDatosIncorrectos() {
        return "Datos Incorrectos verifique";
    }
    
    //error cuando la transaccion no se pudo completar
    function errorSolicitud() {
        return "Error hubo un error en su solicitud...";
    }
    
    function errorVacio($var) {
        return "El campo ".$var." no puede ser vacia verifique";        
    }
    
    function errorExiste($var) {
        return "El campo ".$var." que desea ingresar ya se encuentra en la base de datos";
    }
    
    function errorNoExiste($var) {
        return "El campo ".$var." no existe en la base de datos";
    }    
    
    function errorExisteRegistro($var) {
        return "Ya existe el registro ".$var." que desea ingresar";
    }    
    
    function errorNumerico($var) {
        return "El campo ".$var." debe ser numerico";
    }    
    
    function errorCantidad($var, $cantidad = 0) {
        return "La cantidad de ".$var." debe ser mayor a ".$cantidad;
    }    
    
    function errorCatalogoUnico($nro_catalogo) {
        return "El catalogo con el tipo de arma, marca, modelo, calibre que desea ingresar ya existe verifique, <u> es el catalogo </u> <b> Nro - ".$nro_catalogo." </b>";
    }
    
    function menorStock() {
        return "<u>No hay suficiente cantidad de cajas para ese lote y producto para cubrir dicha salida</u>";
    }
    
    function menorStockMateriaPrima() {
        return "<u>No hay suficiente cantidad de materia prima para cubrir dicha salida</u>";
    }
    
    function mailIncorrecto() {
        return "El mail que desea ingresar tiene un formato incorrecto, verifique";
    }
    
    function fechasIncorrectas() {
        return "La fecha de ingreso no puede ser mayor o igual a la fecha de baja, verifique";
    }
    
    function sinPerfilSeleccionado() {
        return "No selecciono ningun perfil para el usuario, por lo menos debe seleccionar uno";
    }
}

?>
