<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <style>
            .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } 
            .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #8C8C8C; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
            .datagrid table td, .datagrid table th { padding: 3px 10px; }
            
            .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #A3A3A3; } 
            .datagrid table thead th:first-child { border: none; }
            
            .datagrid table tbody td { background: #F2FBEF; color: #7D7D7D; border-left: 1px solid #DBDBDB; border-bottom: 1px solid #DBDBDB; font-size: 12px; font-weight: normal; }
            .datagrid table tbody .alt td { background: #E6F8E0; color: #7D7D7D; }
            .datagrid table tbody .total td { background: #F5F6CE; color: #7D7D7D; font-weight: bold; text-align: center; }
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
                $("#nro_compra").focus();
                $("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function modificarCompra() {
                
                var nro_compra   = $("#nro_compra").val();
                var fecha        = $("#fecha").val();
                var empresa      = $("#empresa").val();
                var pais_empresa = $("#pais_empresa").val();
                var descripcion  = $("#descripcion").val();
                var modalidad    = $("#modalidad").val();
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>modificar_compras/validarDatos",
                    data: "nro_compra="+nro_compra+"&fecha="+fecha+"&empresa="+empresa+"&pais_empresa="+pais_empresa+"&descripcion="+descripcion+"&modalidad="+modalidad,
                    success: function(data){
                        if(data[0] == 1){            
                            jAlert("Compra agregada al sistema con exito - Nro interno de compra generado = "+data[1], "Correcto", function() { irAFrame('<?php echo base_url('alta_compras'); ?>','O.C.I >> Alta >> Compras'); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }
            
            function agregarCatalogo() {
            
                var catalogo           = $("#catalogo").val();
                var cant_total_armas   = $("#cant_total_armas").val();
                var costo_total        = $("#costo_total").val();
            
                 $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_compras/agregarCatalogos",
                   data: "catalogo="+catalogo+"&cant_total_armas="+cant_total_armas+"&costo_total="+costo_total,
                   success: function(data) {
                       if(data[0] == 1) {
                           $("#catalogos").append(data[1]);
                           $("#totales").html("");
                           $("#totales").html(data[2]);
                           cargoCatalogos();
                           $("#cant_total_armas").val("");
                           $("#costo_total").val("");
                       }else {
                           jAlert(data[0], "Error");
                       }
                   }
                });  
            }
            
            //cargo y creo Empresas
            function crearEmpresa() {
                $.colorbox({href:"<?php echo base_url('alta_empresa'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA EMPRESA", onClosed: function(){ cargoEmpresas(); } });
            }            
            
            function cargoEmpresas() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_compras/cargoEmpresas",
                   success: function(data) {
                       $("#empresa").html(data);
                   }
                });
            }     
            //fin cargo y creo Empresas
            
            //cargo y creo Catalogos
            function crearCatalogo() {
                $.colorbox({href:"<?php echo base_url('alta_catalogos'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:500, title:"ALTA CATALOGO", onClosed: function(){ cargoCatalogos(); } });
            }            
            
            function cargoCatalogos() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_compras/cargoCatalogos",
                   success: function(data) {
                       $("#catalogo").html(data);
                   }
                });
            }   
            //fin cargo y creo Catalogos
            
            function anularCatalogo(nro_catalogo) {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>modificar_compras/anularCatalogo",
                   data: "nro_catalogo="+nro_catalogo,
                   success: function(data) {
                       if(data[0] == 1) {
                           $("#catalogos").html("");
                           $("#catalogos").html(data[1]);
                           $("#totales").html("");
                           $("#totales").html(data[2]);                           
                       }else {
                           $("#catalogos").html("");
                           $("#totales").html("<tr class='total'> <td> 0 </td> <td> 0 </td> </tr>");
                       }
                   }
                });                
            }
            
            function busquedaCatalogos() {
                $.colorbox({href:"<?php echo base_url('busqueda_catalogos'); ?>", top:true, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA CATALOGOS", onClosed: function(){ cargoCatalogosFiltro(); } });
            }
            
            function cargoCatalogosFiltro() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_compras/cargoCatalogosFiltro",
                   success: function(data) {
                       $("#catalogo").html("");
                       $("#catalogo").html(data);
                   }
                });                
            }
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Alta compras </h1>    
            
            <fieldset>	

                <dl>
                <dt><label for="nro_compra"> Numero compra </label></dt>
                <dd><input type="text" id="nro_compra" class="text" value="<?php echo $nro_compra; ?>" /></dd>
                </dl>

                <dl>
                <dt><label for="fecha"> Fecha </label></dt>
                <dd><input type="text" id="fecha" class="text" value="<?php echo $fecha; ?>" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="empresa"> Empresa </label></dt>
                <dd><select id="empresa"> <?php echo $empresas ?> </select> <img style="cursor: pointer;" onclick="crearEmpresa();" src="<?php echo base_url(); ?>images/sumar.png" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="pais_empresa"> Pais empresa </label></dt>
                <dd><select id="pais_empresa"> <?php echo $paises ?> </select></dd>
                </dl>                 

                <dl>
                <dt><label for="descripcion"> Descripcion </label></dt>
                <dd><input type="text" id="descripcion" class="text" value="<?php echo $descripcion; ?>"/></dd>
                </dl>                 
                
                <dl>
                <dt><label for="modalidad"> Modalidad </label></dt>
                <dd><input type="text" id="modalidad" class="text" value="<?php echo $modalidad; ?>" /></dd>
                </dl>                 
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Detalles de la compra </p>
                
                <dl>
                <dt><label for="catalogo"> Catalogo </label></dt>
                <dd><select id="catalogo"> <?php echo $catalogos ?> </select> <img style="cursor: pointer;" onclick="busquedaCatalogos();" src="<?php echo base_url(); ?>images/search.png" />  <img style="cursor: pointer;" onclick="crearCatalogo();" src="<?php echo base_url(); ?>images/sumar.png" /></dd>
                </dl>  
                
                <dl>
                <dt><label for="cant_total_armas"> Cant total armas </label></dt>
                <dd><input type="text" id="cant_total_armas" class="number" /></dd>
                </dl>                 
                
                <dl>
                <dt><label for="costo_total"> Costo </label></dt>
                <dd><input type="text" id="costo_total" class="number" /></dd>
                </dl>                 
                
                <button style="margin-right: 20px;" onclick="agregarCatalogo();"> Agregar catalogo </button>
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="modificarCompra();"> Modificar compra </button>
            </fieldset>  
            
            <hr />
            
            <div>
                
                <h1> Catalogos </h1>       
                
                <fieldset>	

                    <div id="imprimir">
                                 
                        <div class="datagrid" style="margin-top: 30px;">
                            <table> 
                                <thead style="text-align: center;">
                                    <tr>
                                        <th> Nro catalogo </th> <th> Tipo arma </th> <th> Marca </th> <th> Modelo </th> <th> Calibre </th> <th> Sistema </th> <th> Cant armas </th> <th> Costo </th> <th> </th> 
                                    </tr>
                                </thead>
                                <tbody id="catalogos"> <?php echo $compras_catalogos; ?> </tbody>
                                <tfoot>
                                    <tr> <td colspan="9"> <div id="paging"> <br /> </div> </td> </tr>
                                </tfoot>                                
                            </table> 
                        </div>
                        
                        <br />
                        
                        <div class="datagrid" style="margin-top: 30px; width: 50%; float: right;">
                            <table> 
                                <thead style="text-align: center;">
                                    <tr>
                                        <th> Total de armas </th> <th> Costo total </th> 
                                    </tr>
                                </thead>
                                <tbody id="totales"> <?php echo $totales; ?> </tbody>
                            </table> 
                        </div>                        
                    
                    </div>    
                        
                </fieldset>	
                
            </div>
            
        </div>        
        
    </body>
	
</html>