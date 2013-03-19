<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#tipo_arma").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaTipoArma() {
                
                var tipo_arma    = $("#tipo_arma").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_tipo_arma/validarDatos",
                    data: "tipo_arma="+tipo_arma,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Tipo arma ingresado con exito", "Correcto", function() { $("#tipo_arma").val(""); });
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

            <h1> Alta tipo arma </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="tipo_arma"> Tipo arma </label></dt>
                <dd><input type="text" id="tipo_arma" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaTipoArma();"> Alta tipo arma </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>