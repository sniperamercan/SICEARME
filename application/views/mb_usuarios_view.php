<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <style>
            .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } 
            .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: auto; border: 1px solid #8C8C8C; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
            .datagrid table td, .datagrid table th { padding: 3px 10px; }
            
            .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #A3A3A3; } 
            .datagrid table thead th:first-child { border: none; }
            
            .datagrid table tbody td { background: #F2FBEF; color: #7D7D7D; border-left: 1px solid #DBDBDB; border-bottom: 1px solid #DBDBDB; font-size: 12px;font-weight: normal; }
            .datagrid table tbody .alt td { background: #E6F8E0; color: #7D7D7D; }
            .datagrid table tbody td:first-child { border-left: none; }
            .datagrid table tbody tr:last-child td { border-bottom: none; }
            
            .datagrid table tfoot td div { border-top: 1px solid #8C8C8C;background: #EBEBEB;} 
            .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }
            .datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }
            .datagrid table tfoot  li { display: inline; }
            .datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #F5F5F5;border: 1px solid #8C8C8C;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; }
            .datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #7D7D7D; color: #F5F5F5; background: none; background-color:#8C8C8C;}
        </style>        
        
    
        <script type="text/javascript">

            $(document).ready(function() {
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function ingresarDatos() {
                
                var usuario   = $("#usuario").val();
                var nombre    = $("#nombre").val();
                var apellido  = $("#apellido").val();
                var clave     = $("#clave").val();
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>agregar_usuario/validarDatos",
                    data: "usuario="+usuario+"&nombre="+nombre+"&apellido="+apellido+"&clave="+clave+"&persmisos="+JSON.stringify(permisos),
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Usuario agregado al sistema con exito", "Correcto", function() { irAFrame('<?php echo base_url('agregar_usuario'); ?>','Administracion >> Agregar usuarios'); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }
            
            function verPermisos(usuario) {
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>mb_usuarios/verPermisos",
                    data: "usuario="+usuario,
                    success: function(data){
                        jAlert(data, "PERMISOS DEL USUARIO");
                  }
                });                
            }
            
            function cambiarEstado(usuario) {
                jConfirm('Estas seguro que quieres cambiar el estado del usuario - '+usuario, 'MODIFICAR ESTADO DEL USUARIO', function(r) {
                    if(r) {
                        $.ajax({
                            type: "post",  
                            url: "<?php base_url(); ?>mb_usuarios/cambiarEstado",
                            data: "usuario="+usuario,
                            success: function(data){
                                jAlert(data, "MODIFICAR ESTADO DEL USUARIO", function() { irAFrame('<?php echo base_url('mb_usuarios'); ?>','Administracion >> Modificar usuarios'); });
                          }
                        });                         
                    }
                });                
            }
            
            function vaciarClave(usuario) {
                jConfirm('Estas seguro que quieres vaciar la clave del usuario - '+usuario, 'VACIAR CLAVE DEL USUARIO', function(r) {
                    if(r) {
                        $.ajax({
                            type: "post",  
                            url: "<?php base_url(); ?>mb_usuarios/vaciarClave",
                            data: "usuario="+usuario,
                            success: function(data){
                                jAlert(data, "VACIAR CLAVE DEL USUARIO");
                          }
                        });                         
                    }
                });                
            }
            
            function editarUsuario(usuario) {
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>mb_usuarios/setearUsuario",
                    data: "usuario="+usuario,
                    success: function(){
                         $.colorbox({href:"<?php echo base_url('modificar_usuarios'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:700, title:"MODIFICAR USUARIOS", onClosed: function(){ irAFrame('<?php echo base_url('mb_usuarios'); ?>','Administracion >> Modificar usuarios'); } });
                  }
                });
            }
            
            function eliminarUsuario(usuario) {
                jConfirm('Estas seguro que quieres eliminar el usuario - '+usuario, 'ELIMINAR USUARIO DEL SISTEMA', function(r) {
                    if(r) {
                        $.ajax({
                            type: "post",  
                            url: "<?php base_url(); ?>mb_usuarios/eliminarUsuario",
                            data: "usuario="+usuario,
                            success: function(data){
                                jAlert(data, "ELIMINAR USUARIO USUARIO DEL SISTEMA", function() { irAFrame('<?php echo base_url('mb_usuarios'); ?>','Administracion >> Modificar usuarios'); });
                          }
                        });                         
                    }
                });                 
            }
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			
            
            <?php echo $listado; ?>
            
        </div>        
        
    </body>
	
</html>