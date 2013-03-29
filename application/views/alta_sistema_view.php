<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#sistema").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaSistema() {
                
                var sistema    = $("#sistema").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_sistema/validarDatos",
                    data: "sistema="+sistema,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Sistema ingresado con exito", "Correcto", function() { parent.$.fn.colorbox.close(); });
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

            <h1> Alta sistema </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="sistema"> Sistema </label></dt>
                <dd><input type="text" id="sistema" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaSistema();"> Alta sistema </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>