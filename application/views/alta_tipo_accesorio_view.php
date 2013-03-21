<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#tipo_accesorio").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaTipoAccesorio() {
                
                var tipo_accesorio    = $("#tipo_accesorio").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_tipo_accesorio/validarDatos",
                    data: "tipo_accesorio="+tipo_accesorio,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Tipo accesorio ingresado con exito", "Correcto", function() { $("#tipo_accesorio").val(""); });
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

            <h1> Alta tipo accesorio </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="tipo_accesorio"> Tipo accesorio </label></dt>
                <dd><input type="text" id="tipo_accesorio" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaTipoAccesorio();"> Alta tipo accesorio </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>