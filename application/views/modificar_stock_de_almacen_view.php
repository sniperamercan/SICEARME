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
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function modificarStock() {
                
                var nro_parte    = cambiarCaracterNroParte();
                var nombre_parte = cambiarCaracterNombreParte();
                var nro_catalogo = $("#nro_catalogo").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>modificar_stock_de_almacen/validarDatos",
                    data: "nro_parte="+nro_parte+"&nombre_parte="+nombre_parte+"&nro_catalogo="+nro_catalogo,
                    success: function(data){
                        if(data == 1){            
                            jAlert("CORRECTO: Datos del repuesto modificado correctamente", "Correcto", function() { volver(); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                  }
                });               
            }
            
            function cambiarCaracterNroParte() {
                var val = $("#nro_parte").val();    
                while (val !=(val = val.replace('&', '')));
                return val;
            }  
            
            function cambiarCaracterNombreParte() {
                var val = $("#nombre_parte").val();    
                while (val !=(val = val.replace('&', '')));
                return val;
            }              
            
            function volver() {
                irAFrame('<?php echo base_url('mb_stock_de_almacen'); ?>','Almacen >> Alta >> Respuestos');
            }
            
            function busquedaCatalogos() {
                $.colorbox({href:"<?php echo base_url('busqueda_catalogos'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA CATALOGOS", onClosed: function(){ cargoCatalogosFiltro(); } });
            }
            
            function cargoCatalogosFiltro() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>modificar_stock_de_almacen/cargoCatalogosFiltro",
                   success: function(data) {
                       $("#nro_catalogo").val("");
                       $("#nro_catalogo").val(data);
                   }
                });                
            }              
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Modificar stock de almacen </h1>    
            
            <fieldset>	
                
                <dl>
                <dt><label for="nro_parte"> Nro parte </label></dt>
                <dd><input type="text" id="nro_parte" class="text" value="<?php echo $nro_parte; ?>" /></dd>
                </dl>                
                
                <dl> 		
                <dt><label for="nombre_parte"> Nombre <font color="red"> * </font> </label></dt>	
                <dd><input type="text" id="nombre_parte" class="text" value="<?php echo $nombre_parte; ?>" /></dd> 					
                </dl>
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Cargo el catalogo al cual pertenece este armamento </p>
                
                <dl>
                <dt><label> Buscar catalogo </label></dt>
                <dd><img style="cursor: pointer;" onclick="busquedaCatalogos();" src="<?php echo base_url(); ?>images/search.png" /> </dd>
                </dl>         
                
                <dl>
                <dt><label for="nro_catalogo"> Nro catalogo <font color="red"> * </font> </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_catalogo" class="txtautomatico" value="<?php echo $nro_catalogo; ?>" /> </dd>
                </dl>                 
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="modificarStock();"> Modificar stock </button>
                <button style="margin-right: 20px;" onclick="volver();"> Volver </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>