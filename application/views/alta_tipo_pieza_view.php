<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#tipo_pieza").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaTipoPieza() {
                
                var tipo_pieza    = $("#tipo_pieza").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_tipo_pieza/validarDatos",
                    data: "tipo_pieza="+tipo_pieza,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Tipo pieza ingresado con exito", "Correcto", function() { parent.$.fn.colorbox.close(); });
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

            <h1> Alta tipo pieza </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="tipo_pieza"> Tipo pieza </label></dt>
                <dd><input type="text" id="tipo_pieza" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaTipoPieza();"> Alta tipo pieza </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>