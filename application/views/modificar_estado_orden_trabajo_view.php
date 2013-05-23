<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <style>
            
            button.reparada {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                    color: #ffffff;
                    padding: 10px 20px;
                    background: -moz-linear-gradient(
                            top,
                            #42ff42 0%,
                            #146600);
                    background: -webkit-gradient(
                            linear, left top, left bottom, 
                            from(#42ff42),
                            to(#146600));
                    -moz-border-radius: 10px;
                    -webkit-border-radius: 10px;
                    border-radius: 10px;
                    border: 1px solid #134201;
                    -moz-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    -webkit-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    text-shadow:
                            0px -1px 0px rgba(000,000,000,0.4),
                            0px 1px 0px rgba(255,255,255,0.3);
            }
          
            button.reparada_fallas {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                    color: #ffffff;
                    padding: 10px 20px;
                    background: -moz-linear-gradient(
                            top,
                            #f7ff05 0%,
                            #6d7a0c);
                    background: -webkit-gradient(
                            linear, left top, left bottom, 
                            from(#f7ff05),
                            to(#6d7a0c));
                    -moz-border-radius: 10px;
                    -webkit-border-radius: 10px;
                    border-radius: 10px;
                    border: 1px solid #66500c;
                    -moz-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    -webkit-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    text-shadow:
                            0px -1px 0px rgba(000,000,000,0.4),
                            0px 1px 0px rgba(255,255,255,0.3);
                    margin-left: 10px;
            }

            
            button.fallas {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                    color: #ffffff;
                    padding: 10px 20px;
                    background: -moz-linear-gradient(
                            top,
                            #ff0d05 0%,
                            #4f1414);
                    background: -webkit-gradient(
                            linear, left top, left bottom, 
                            from(#ff0d05),
                            to(#4f1414));
                    -moz-border-radius: 10px;
                    -webkit-border-radius: 10px;
                    border-radius: 10px;
                    border: 1px solid #8a3030;
                    -moz-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    -webkit-box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    box-shadow:
                            0px 1px 3px rgba(000,000,000,0.5),
                            inset 0px 0px 2px rgba(255,255,255,1);
                    text-shadow:
                            0px -1px 0px rgba(000,000,000,0.4),
                            0px 1px 0px rgba(255,255,255,0.3);
                    margin-left: 10px;
            }
            
        </style>    
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#seccion").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function cerrarOrdenTrabajo(estado_armamento) {
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>modificar_estado_orden_trabajo/validarDatos",
                    data: "estado_armamento="+estado_armamento,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("CORRECTO: Se modifico el estado de la orden de trabajo", "Correcto", function() { parent.$.fn.colorbox.close(); });
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

            <h1> Modificar estado de orden de trabajo Nº - <?php echo $nro_orden; ?> </h1>    
            
            <p class="subtituloform"> Indicar el estado final del armamento luego de la reparación </p>
            
            <fieldset>	
                
                <center>
                    <button onclick="cerrarOrdenTrabajo(0)" type="button" class="reparada">REPARADO</button>
                    <button onclick="cerrarOrdenTrabajo(1)" type="button" class="reparada_fallas">CON DESPERFECTOS</button>
                    <button onclick="cerrarOrdenTrabajo(2)" type="button" class="fallas">SIN REPARACION</button>
                </center>
                
            </fieldset>	
            
        </div>        
        
    </body>
	
</html>