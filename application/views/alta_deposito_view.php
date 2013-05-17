<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#deposito").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaDeposito() {
                
                var deposito = $("#deposito").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_deposito/validarDatos",
                    data: "deposito="+deposito,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Deposito ingresado con exito", "Correcto", function() { parent.$.fn.colorbox.close(); });
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

            <h1> Alta deposito </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="deposito"> Deposito </label></dt>
                <dd><input type="text" id="deposito" class="text" /></dd>
                </dl>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaDeposito();"> Alta deposito </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>