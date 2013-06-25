<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <style>
            
            .reparada {
                    -moz-box-shadow:inset 0px 1px 0px 0px #a4e271;
                    -webkit-box-shadow:inset 0px 1px 0px 0px #a4e271;
                    box-shadow:inset 0px 1px 0px 0px #a4e271;
                    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #89c403), color-stop(1, #77a809) );
                    background:-moz-linear-gradient( center top, #89c403 5%, #77a809 100% );
                    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#89c403', endColorstr='#77a809');
                    background-color:#89c403;
                    -moz-border-radius:6px;
                    -webkit-border-radius:6px;
                    border-radius:6px;
                    border:1px solid #74b807;
                    display:inline-block;
                    color:#ffffff;
                    font-family:arial;
                    font-size:20px;
                    font-weight:bold;
                    padding:6px 24px;
                    text-decoration:none;
                    text-shadow:1px 1px 0px #528009;
                    margin-right: 5px;
            }.reparada:hover {
                    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #77a809), color-stop(1, #89c403) );
                    background:-moz-linear-gradient( center top, #77a809 5%, #89c403 100% );
                    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#77a809', endColorstr='#89c403');
                    background-color:#77a809;
            }.reparada:active {
                    position:relative;
                    top:1px;
            }
          
            .reparada_fallas {
                    -moz-box-shadow:inset 0px 1px 0px 0px #fed897;
                    -webkit-box-shadow:inset 0px 1px 0px 0px #fed897;
                    box-shadow:inset 0px 1px 0px 0px #fed897;
                    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f6b33d), color-stop(1, #d29105) );
                    background:-moz-linear-gradient( center top, #f6b33d 5%, #d29105 100% );
                    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f6b33d', endColorstr='#d29105');
                    background-color:#f6b33d;
                    -moz-border-radius:6px;
                    -webkit-border-radius:6px;
                    border-radius:6px;
                    border:1px solid #eda933;
                    display:inline-block;
                    color:#ffffff;
                    font-family:arial;
                    font-size:20px;
                    font-weight:bold;
                    padding:6px 24px;
                    text-decoration:none;
                    text-shadow:1px 1px 0px #cd8a15;
                    margin-right: 5px;
            }.reparada_fallas:hover {
                    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #d29105), color-stop(1, #f6b33d) );
                    background:-moz-linear-gradient( center top, #d29105 5%, #f6b33d 100% );
                    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#d29105', endColorstr='#f6b33d');
                    background-color:#d29105;
            }.reparada_fallas:active {
                    position:relative;
                    top:1px;
            }
            
            .fallas {
                    -moz-box-shadow:inset 0px 1px 0px 0px #f29c93;
                    -webkit-box-shadow:inset 0px 1px 0px 0px #f29c93;
                    box-shadow:inset 0px 1px 0px 0px #f29c93;
                    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fe1a00), color-stop(1, #ce0100) );
                    background:-moz-linear-gradient( center top, #fe1a00 5%, #ce0100 100% );
                    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fe1a00', endColorstr='#ce0100');
                    background-color:#fe1a00;
                    -moz-border-radius:6px;
                    -webkit-border-radius:6px;
                    border-radius:6px;
                    border:1px solid #d83526;
                    display:inline-block;
                    color:#ffffff;
                    font-family:arial;
                    font-size:20px;
                    font-weight:bold;
                    padding:6px 24px;
                    text-decoration:none;
                    text-shadow:1px 1px 0px #b23e35;
            }.fallas:hover {
                    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ce0100), color-stop(1, #fe1a00) );
                    background:-moz-linear-gradient( center top, #ce0100 5%, #fe1a00 100% );
                    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ce0100', endColorstr='#fe1a00');
                    background-color:#ce0100;
            }.fallas:active {
                    position:relative;
                    top:1px;
            }
            
        </style>    
        
        <script type="text/javascript">

            $(document).ready(function() {
                //$("#seccion").focus();
                //$("input:submit").button();
                //$("button").button(); 
                //$("input:button").button(); 
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