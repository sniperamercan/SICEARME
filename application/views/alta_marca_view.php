<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#marca").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaMarca() {
                
                var marca    = $("#marca").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_marca/validarDatos",
                    data: "marca="+marca,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Marca ingresada con exito", "Correcto", function() { $("#marca").val(""); });
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

            <h1> Alta marca </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="marca"> Marca </label></dt>
                <dd><input type="text" id="marca" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaMarca();"> Alta marca </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>