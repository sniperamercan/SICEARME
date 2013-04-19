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

            function altaStock() {
                
                var nro_parte    = $("#nro_parte").val();
                var nombre_parte = $("#nombre_parte").val();
                var precio       = $("#precio").val();
                var cantidad     = $("#cantidad").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>alta_stock_de_almacen/validarDatos",
                    data: "nro_parte="+nro_parte+"&nombre_parte="+nombre_parte+"&precio="+precio+"&cantidad="+cantidad,
                    success: function(data){
                        if(data == 1){            
                            jAlert("Stock de parte ingresado correctamente al stock del almacen", "Correcto", function() { irAFrame('<?php echo base_url('alta_stock_de_almacen'); ?>','Almacen >> Alta >> Respuestos'); });
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

            <h1> Alta stock de almacen </h1>    
            
            <fieldset>	
                
                <dl>
                <dt><label for="nro_parte"> Nro parte </label></dt>
                <dd><input type="text" id="nro_parte" class="text" /></dd>
                </dl>                
                
                <dl> 		
                <dt><label for="nombre_parte"> Nombre <font color="red"> * </font> </label></dt>	
                <dd><input type="text" id="nombre_parte" class="text" /></dd> 					
                </dl>

                <dl> 		
                <dt><label for="precio"> Precio <font color="red"> * </font> </label></dt>	
                <dd><input type="text" id="precio" class="number" /></dd> 					
                </dl> 
                
                <dl> 		
                <dt><label for="cantidad"> Cantidad <font color="red"> * </font> </label></dt>	
                <dd><input type="text" id="cantidad" class="number" /></dd> 					
                </dl>                
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaStock();"> Alta stock </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>