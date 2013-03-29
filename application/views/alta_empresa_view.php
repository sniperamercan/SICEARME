<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#empresa").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaEmpresa() {
                
                var empresa    = $("#empresa").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_empresa/validarDatos",
                    data: "empresa="+empresa,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Empresa ingresada con exito", "Correcto", function() { parent.$.fn.colorbox.close(); });
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

            <h1> Alta empresa </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="empresa"> Empresa </label></dt>
                <dd><input type="text" id="empresa" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaEmpresa();"> Alta empresa </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>