<!DOCTYPE html>
<html lang="es">
    
    <head>
    
        <script type="text/javascript">

            $(document).ready(function() {
                $("#lugar").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();
            });	

            function ingresarDatos() {

                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>desarrollo_incremento_version_sistema/validarDatos",
                    success: function(data){
                        if(data != ""){            
                            jAlert("Version incrementada correctamente, numero de version nueva <b>"+data+"</b>", "Correcto", function() { window.location.href = '<?php base_url() ?>/SICEARME/panelprincipal' });
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

            <h1> Incremento de version del sistema </h1>  
            
            <fieldset>	

                <dl>
                <dt><label for="version"> Version actual </label></dt>
                <dd><label id="version"> <?php echo $version; ?> </label></dd>
                </dl>
                
                <dl>
                <dt><label for="version"> Version nueva </label></dt>
                <dd><label id="version"> <?php echo $version_nueva; ?> </label></dd>
                </dl>                
                
            </fieldset>	

            <fieldset class="action">	
                <button onclick="ingresarDatos();"> Incrementar version </button>
            </fieldset>           
            
        </div>        
        
    </body>
	
</html>