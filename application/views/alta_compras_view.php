<!DOCTYPE html>
<html lang="es">
    
    <head>
    
        <script type="text/javascript">

            $(document).ready(function() {
                $("#nro_compra").focus();
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function ingresarDatos() {
                
                var usuario   = $("#usuario").val();
                var nombre    = $("#nombre").val();
                var apellido  = $("#apellido").val();
                var clave     = $("#clave").val();
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>agregar_usuario/validarDatos",
                    data: "usuario="+usuario+"&nombre="+nombre+"&apellido="+apellido+"&clave="+clave+"&persmisos="+JSON.stringify(permisos),
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Usuario agregado al sistema con exito", "Correcto", function() { irAFrame('<?php echo base_url('agregar_usuario'); ?>','Adminitracion >> Agregar usuarios'); });
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

            <h1> Compras </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="nro_compra"> Numero compra </label></dt>
                <dd><input type="text" id="nro_compra" class="text" /></dd>
                </dl>

                <dl>
                <dt><label for="fecha"> Fecha </label></dt>
                <dd><input type="text" id="fecha" class="text" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="empresa_proveedora"> Empresa proveedora </label></dt>
                <dd><input type="text" id="empresa_proveedora" class="text" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="pais_empresa"> Pais empresa </label></dt>
                <dd><select id="pais_empresa"> <?php echo $paises ?> </select></dd>
                </dl>                 

                <dl>
                <dt><label for="descripcion"> Descripcion </label></dt>
                <dd><input type="text" id="descripcion" class="text" /></dd>
                </dl>                 
                
                <dl>
                <dt><label for="modalidad"> Modalidad </label></dt>
                <dd><input type="text" id="modalidad" class="text" /></dd>
                </dl>                 
                
                <dl>
                <dt><label for="cant_total_armas"> Cant total armas </label></dt>
                <dd><input type="text" id="cant_total_armas" class="text" /></dd>
                </dl>                 
                
                <dl>
                <dt><label for="costo_total"> Costo total </label></dt>
                <dd><input type="text" id="costo_total" class="number" /></dd>
                </dl>                 
                
                
            </fieldset>	

            <fieldset class="action">	
                <button onclick="ingresarDatos();"> Agregar usuario </button>
            </fieldset>           
            
        </div>        
        
    </body>
	
</html>