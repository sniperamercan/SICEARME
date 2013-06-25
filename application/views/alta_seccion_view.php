<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#seccion").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaSeccion() {
                
                var seccion    = $("#seccion").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_seccion/validarDatos",
                    data: "seccion="+seccion,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Seccion ingresada con exito", "Correcto", function() { parent.$.fn.colorbox.close(); });
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

            <h1> Alta seccion </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="seccion"> Seccion </label></dt>
                <dd><input type="text" id="seccion" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaSeccion();"> Alta seccion </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>