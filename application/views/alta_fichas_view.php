<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <style>
            .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } 
            .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #8C8C8C; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
            .datagrid table td, .datagrid table th { padding: 3px 10px; }
            
            .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #A3A3A3; } 
            .datagrid table thead th:first-child { border: none; }
            
            .datagrid table tbody td { background: #F2FBEF; color: #7D7D7D; border-left: 1px solid #DBDBDB; border-bottom: 1px solid #DBDBDB; font-size: 12px;font-weight: normal; }
            .datagrid table tbody .alt td { background: #E6F8E0; color: #7D7D7D; }
            .datagrid table tbody td:first-child { border-left: none; }
            .datagrid table tbody tr:last-child td { border-bottom: none; }
            
            .datagrid table tfoot td div { border-top: 1px solid #8C8C8C;background: #EBEBEB;} 
            .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }
            .datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }
            .datagrid table tfoot  li { display: inline; }
            .datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #F5F5F5;border: 1px solid #8C8C8C;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; }
            .datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #7D7D7D; color: #F5F5F5; background: none; background-color:#8C8C8C;}
        </style>         
    
        <script type="text/javascript">

            $(document).ready(function() {
                $("#nro_serie").focus();
                $("#nro_catalogo").prop('disabled', true);
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaFicha() {
                
                var nro_serie     = $("#nro_serie").val();
                var nro_compra    = $("#nro_compra").val();   
                var nro_catalogo  = $("#nro_catalogo").val();   
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_fichas/validarDatos",
                    data: "nro_serie="+nro_serie+"&nro_compra="+nro_compra+"&nro_catalogo="+nro_catalogo,
                    success: function(data){
                        if(data == "1"){            
                            jAlert("Ficha del arma agregada al sistema con exito", "Correcto", function() { irAFrame('<?php echo base_url('alta_fichas'); ?>','O.C.I >> Alta >> Fichas'); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }
            
            function cargoNroCatalogos(nro_compra) {
            
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_fichas/cargoNroCatalogos",
                   data: "nro_compra="+nro_compra,
                   success: function(data) {
                       $('#nro_catalogo').prop('disabled', false);
                       $('#nro_catalogo').html(data);
                   }
                });
            }
            
            function cargoInformacion(nro_catalogo) {
 
                 $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>alta_fichas/cargoInformacion",
                   data: "nro_catalogo="+nro_catalogo,
                   success: function(data) {
                       $('#marca').val(data[0]);
                       $('#calibre').val(data[1]);
                       $('#modelo').val(data[2]);
                   }
                });
            }
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Alta fichas </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="nro_serie"> Nro serie </label></dt>
                <dd><input type="text" id="nro_serie" class="text" /></dd>
                </dl>                
                
                <dl> 		
                <dt><label for="marca"> Marca </label></dt>	
                <dd><input type="text" id="marca" class="txtautomatico" readonly="readonly" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="calibre"> Calibre </label></dt>	
                <dd><input type="text" id="calibre" class="txtautomatico" readonly="readonly" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="modelo"> Modelo </label></dt>	
                <dd><input type="text" id="modelo" class="txtautomatico" readonly="readonly" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="nro_compra"> Nro compra </label></dt>	
                <dd><select id="nro_compra"> <?php echo $nro_compras; ?> </select> <img style="cursor: pointer;" onclick="listarCompras();" src="<?php echo base_url(); ?>images/search.png" /></dd> 					
                </dl>                
                
                <dl> 		
                <dt><label for="nro_catalogo"> Nro catalogo </label></dt>	
                <dd><select id="nro_catalogo">  </select></dd> 					
                </dl>
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Accesorios - </p>

                <dl>
                <dt><label for="nro_accesorio"> Nro accesorio </label></dt>
                <dd><input type="text" id="nro_accesorio" class="text" /></dd>
                </dl> 
                
                <dl> 		
                <dt><label for="tipo_accesorio"> Tipo accesorio </label></dt>	
                <dd><select id="tipo_accesorio"> <?php echo $tipo_accesorio; ?> </select> <img style="cursor: pointer;" onclick="crearTipoAccesorio();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>                
                
                <dl>
                <dt><label for="descricion_accesorio"> Descripcion </label></dt>
                <dd><input type="text" id="descripcion_accesorio" class="text" /></dd>
                </dl>                
                
                <p><button id="agregar_accesorio" onclick="agregarAccesorio();"> Agregar accesorio </button></p>

                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <div class="datagrid">
                    <table> 
                        <thead>
                            <tr>
                                <th></th> <th> Nro accesorio </th> <th> Tipo </th> <th> Descripcion </th>
                            </tr>
                        </thead>
                        <tbody id="accesorios"></tbody> 
                        <tfoot>
                            <tr> <td colspan="4"> <div id="paging"> <br /> </div> </td> </tr>
                        </tfoot>
                    </table> 
                </div>
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Piezas - </p>

                <dl>
                <dt><label for="nro_pieza"> Nro pieza </label></dt>
                <dd><input type="text" id="nro_pieza" class="text" /></dd>
                </dl> 
                
                <dl> 		
                <dt><label for="tipo_pieza"> Tipo pieza </label></dt>	
                <dd><select id="tipo_pieza"> <?php echo $tipo_pieza; ?> </select> <img style="cursor: pointer;" onclick="crearTipoPieza();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>                
                
                <dl>
                <dt><label for="descricion_pieza"> Descripcion </label></dt>
                <dd><input type="text" id="descripcion_pieza" class="text" /></dd>
                </dl>                
                
                <p><button id="agregar_pieza" onclick="agregarPieza();"> Agregar pieza &emsp14;&emsp14;&emsp14;&emsp14; </button></p>

                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <div class="datagrid">
                    <table> 
                        <thead>
                            <tr>
                                <th></th> <th> Nro pieza </th> <th> Tipo </th> <th> Descripcion </th>
                            </tr>
                        </thead>
                        <tbody id="piezas"></tbody> 
                        <tfoot>
                            <tr> <td colspan="4"> <div id="paging"> <br /> </div> </td> </tr>
                        </tfoot>
                    </table> 
                </div> 
                                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaFicha();"> Alta ficha </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>