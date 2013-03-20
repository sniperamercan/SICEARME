<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#modelo").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaModelo() {
                
                var modelo    = $("#modelo").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_modelo/validarDatos",
                    data: "modelo="+modelo,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Modelo ingresado con exito", "Correcto", function() { $("#modelo").val(""); });
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

            <h1> Alta modelo </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="modelo"> Modelo </label></dt>
                <dd><input type="text" id="modelo" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaModelo();"> Alta modelo </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>