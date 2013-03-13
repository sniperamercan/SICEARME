<!DOCTYPE html>
<html lang="es">
    
    <head>
    
        <script type="text/javascript">

            $(document).ready(function() {
                $("#clave_antigua").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();
            });	

            function ingresarDatos() {
                
                var clave_antigua  = $("#clave_antigua").val();
                var clave_nueva    = $("#clave_nueva").val();
                var repetir        = $("#repetir").val();
                
                $.ajax({
                    type: "post",                    
                    url: "<?php base_url(); ?>modificar_clave/validarDatos",
                    data: "clave_antigua="+clave_antigua+"&clave_nueva="+clave_nueva+"&repetir="+repetir,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Su clave se modifico con exito", "Correcto", function() { window.location.href = window.location.href; });
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

            <h1> Cambiar clave </h1>
            
            <fieldset>	

                <dl>
                <dt><label for="nro_certificado"> Clave antigua </label></dt>
                <dd><input type="password" id="clave_antigua" class="text" /></dd>
                </dl>

                <dl>
                <dt><label for="nro_certificado"> Clave nueva </label></dt>
                <dd><input type="password" id="clave_nueva" class="text" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="nro_certificado"> Repetir </label></dt>
                <dd><input type="password" id="repetir" class="text" /></dd>
                </dl>                
                
            </fieldset>	

            <fieldset class="action">	
                <button onclick="ingresarDatos();"> Confirmar </button>
            </fieldset>           
            
        </div>        
        
    </body>
	
</html>