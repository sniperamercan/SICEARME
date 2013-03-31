<!DOCTYPE html>
<html lang="es">
    
    <head>
    
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
                
                var permisos = new Array();
                var i = 0;
                
                if ($('#1').is(':checked')) {
                    permisos[i] = $('#1').val();
                    i++;
                }
                
                if ($('#2').is(':checked')) {
                    permisos[i] = $('#2').val();
                    i++;
                }

                if ($('#3').is(':checked')) {
                    permisos[i] = $('#3').val();
                    i++;
                }
                
                if ($('#4').is(':checked')) {
                    permisos[i] = $('#4').val();
                    i++;
                }
                
                if ($('#5').is(':checked')) {
                    permisos[i] = $('#5').val();
                    i++;
                }
                
                if ($('#6').is(':checked')) {
                    permisos[i] = $('#6').val();
                    i++;
                }
                
                if ($('#7').is(':checked')) {
                    permisos[i] = $('#7').val();
                    i++;
                }

                if ($('#8').is(':checked')) {
                    permisos[i] = $('#8').val();
                    i++;
                }
                
                if ($('#9').is(':checked')) {
                    permisos[i] = $('#9').val();
                    i++;
                }             
                
                if ($('#10').is(':checked')) {
                    permisos[i] = $('#10').val();
                    i++;
                }            
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>modificar_usuarios/validarDatos",
                    data: "usuario="+usuario+"&nombre="+nombre+"&apellido="+apellido+"&persmisos="+JSON.stringify(permisos),
                    success: function(data){
                        if(data == "1"){            
                            jAlert("El usuario se modifico con exito", "Correcto", function() { });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Modificar usuario </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="usuario"> Usuario </label></dt>
                <dd><input type="text" id="usuario" class="txtautomatico" readonly="readonly" value="<?php echo $usuario; ?>" /></dd>
                </dl>

                <dl>
                <dt><label for="nombre"> Nombre </label></dt>
                <dd><input type="text" id="nombre" class="text" value="<?php echo $nombre; ?>" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="apellido"> Apellido </label></dt>
                <dd><input type="text" id="apellido" class="text" value="<?php echo $apellido; ?>" /></dd>
                </dl>                
                
                <img src="<?php base_url() ?>images/barra.png" />
                
                <p class="subtituloform"> Permisos </p>
                
                <?php echo $permisos_usuario ?> 
                
            </fieldset>	

            <fieldset class="action">	
                <button onclick="ingresarDatos();"> Modificar usuario </button>
            </fieldset>           
            
        </div>        
        
    </body>
	
</html>