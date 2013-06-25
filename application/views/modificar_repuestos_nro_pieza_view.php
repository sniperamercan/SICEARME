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
                //$("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function modificarRepuestoNroPieza() {
                
                var nro_pieza      = $("#nro_pieza").val();
                var nro_parte      = $("#nro_parte").val();
                var nombre_parte   = $("#nombre_parte").val();
                var nro_catalogo   = $("#nro_catalogo").val();
                
                $.ajax({
                    type: "post",  
                    url: "<?php base_url(); ?>modificar_repuestos_nro_pieza/validarDatos",
                    data: "nro_pieza="+nro_pieza+"&nro_parte="+nro_parte+"&nombre_parte="+nombre_parte+"&nro_catalogo="+nro_catalogo,
                    success: function(data){
                        if(data == 1){            
                            jAlert("Repuesto con Nro de pieza modificado correctamente", "Correcto", function() { volver(); });
                        }else{
                            jAlert(data, "Error");
                        }                            
                    }
                });               
            }
            
            function volver() {
                irAFrame('<?php echo base_url('mb_repuestos_nro_pieza'); ?>','Almacen >> Modificar/Anular >> Piezas');
            }  
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Modificar pieza </h1>    
            
            <fieldset>	
                
                <dl>
                <dt><label for="nro_pieza"> Nº pieza </label></dt>
                <dd><input type="text" id="nro_pieza" class="number" value="<?php echo $nro_pieza; ?>" /></dd>
                </dl>                 
                
                <dl>
                <dt><label for="nro_parte"> Nº parte </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_parte" class="txtautomatico" value="<?php echo $nro_parte; ?>" /> </dd>
                </dl>                 

                <dl>
                <dt><label for="nombre_parte"> Nombre parte </label></dt>
                <dd><input readonly="readonly" type="text" id="nombre_parte" class="txtautomatico" value="<?php echo $nombre_parte; ?>" /></dd>
                </dl> 
                
                <dl>
                <dt><label for="nro_catalogo"> Nº catálogo </label></dt>
                <dd><input readonly="readonly" type="text" id="nro_catalogo" class="txtautomatico" value="<?php echo $nro_catalogo; ?>" /></dd>
                </dl>                
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="modificarRepuestoNroPieza();"> Modificar pieza </button> 
                <button style="margin-right: 20px;" onclick="volver();"> Volver </button>
            </fieldset>  
            
            <hr />
            
        </div>        
        
    </body>
	
</html>