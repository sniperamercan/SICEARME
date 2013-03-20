<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#calibre").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaCalibre() {
                
                var calibre    = $("#calibre").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_calibre/validarDatos",
                    data: "calibre="+calibre,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Calibre ingresado con exito", "Correcto", function() { $("#calibre").val(""); });
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

            <h1> Alta calibre </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="calibre"> Calibre </label></dt>
                <dd><input type="text" id="calibre" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaCalibre();"> Alta calibre </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>