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
                $("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaCatalogo() {
                
                var tipo_arma    = $("#tipo_arma").val();
                var marca        = $("#marca").val();
                var calibre      = $("#calibre").val();
                var modelo       = $("#modelo").val();
                var sistema      = $("#sistema").val();
                var empresa      = $("#empresa").val();
                var pais_empresa = $("#pais_empresa").val();
                var fabricacion  = $("#fabricacion").val();
                var vencimiento  = $("#vencimiento").val();
                
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>alta_catalogos/validarDatos",
                    data: "tipo_arma="+tipo_arma+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&sistema="+sistema+"&empresa="+empresa+"&pais_empresa="+pais_empresa+"&fabricacion="+fabricacion+"&vencimiento="+vencimiento,
                    success: function(data){
                        if(data[0] == "1"){            
                            jAlert("Catalogo ingresado con exito - Nro de catalogo = "+data[1], "Correcto", function() { 
                                cargoTiposArmas();
                                cargoMarcas();
                                cargoCalibres();
                                cargoModelos();
                                cargoSistemas();
                                cargoEmpresas();
                                cargoPaises();
                                $("#fabricacion").val('');
                                $("#vencimiento").val('');
                                parent.$.fn.colorbox.close();
                            });
                        }else{
                            jAlert(data[0], "Error");
                        }                            
                  }
                });               
            }
            
            //INICIO llamadas a crear los tipos
            
            function crearTipoArma() {
                $.colorbox({href:"<?php echo base_url('alta_tipo_arma'); ?>", top:false, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA TIPO ARMA", onClosed: function(){ cargoTiposArmas(); } });
            }
            
            function crearMarca() {
                $.colorbox({href:"<?php echo base_url('alta_marca'); ?>", top:false, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA MARCA", onClosed: function(){ cargoMarcas(); } });
            }

            function crearCalibre() {
                $.colorbox({href:"<?php echo base_url('alta_calibre'); ?>", top:false, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA CALIBRE", onClosed: function(){ cargoCalibres(); } });
            }
            
            function crearModelo() {
                $.colorbox({href:"<?php echo base_url('alta_modelo'); ?>", top:false, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA MODELO", onClosed: function(){ cargoModelos(); } });
            }

            function crearSistema() {
                $.colorbox({href:"<?php echo base_url('alta_sistema'); ?>", top:false, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA SISTEMA", onClosed: function(){ cargoSistemas(); } });
            }
            
            function crearEmpresa() {
                $.colorbox({href:"<?php echo base_url('alta_empresa'); ?>", top:false, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA EMPRESA", onClosed: function(){ cargoEmpresas(); } });
            }            
            
            //FIN de llamadas a crear los tipos
            
            //INICIO funciones para refrescar los tipos una vez que se cierra el evento de crear los tipos
            
            function cargoTiposArmas() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_catalogos/cargoTiposArmas",
                   success: function(data) {
                       $("#tipo_arma").html(data);
                   }
                });
            }     
            
            function cargoMarcas() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_catalogos/cargoMarcas",
                   success: function(data) {
                       $("#marca").html(data);
                   }
                });
            }

            function cargoCalibres() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_catalogos/cargoCalibres",
                   success: function(data) {
                       $("#calibre").html(data);
                   }
                });
            }
            
            function cargoModelos() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_catalogos/cargoModelos",
                   success: function(data) {
                       $("#modelo").html(data);
                   }
                });
            }

            function cargoSistemas() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_catalogos/cargoSistemas",
                   success: function(data) {
                       $("#sistema").html(data);
                   }
                });
            }
            
            function cargoEmpresas() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_catalogos/cargoEmpresas",
                   success: function(data) {
                       $("#empresa").html(data);
                   }
                });
            }           
            
            function cargoPaises() {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_catalogos/cargoPaises",
                   success: function(data) {
                       $("#pais_empresa").html(data);
                   }
                });            
            }
            
            //FIN de las funciones para refrescar los tipos
            
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Alta ordenes de trabajo </h1>    
            
            <fieldset>	
                
                <dl>
                <dt><label for="fecha"> Fecha </label></dt>
                <dd><input readonly="readonly" type="text" id="fecha" class="text" /></dd>
                </dl>                
                
                <dl> 		
                <dt><label for="nro_registro"> Nro orden </label></dt>	
                <dd><select id="nro_serie" onchange="cargoMarcas(this.value);"> <?php echo $nro_series; ?> </select> <img style="cursor: pointer;" onclick="busquedaFichas();" src="<?php echo base_url(); ?>images/search.png" /> <img style="cursor: pointer;" onclick="busquedaFichas();" src="<?php echo base_url(); ?>images/eye.png" /></dd> 					
                </dl>

                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Orden de trabajo </p>                
                
                <dl> 		
                <dt><label for="marca"> Seccion </label></dt>	
                <dd><select id="marca" onchange="cargoCalibres($('#nro_serie').val(), this.value);"> </select> <img style="cursor: pointer;" onclick="crearTipoArma();" src="<?php echo base_url(); ?>images/sumar.png" /> </dd> 					
                </dl>

                <dl> 		
                <dt><label for="detalles"> Detalles </label></dt>	
                <dd><textarea id="detalles"> </textarea></dd> 					
                </dl>  
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaCatalogo();"> Ingresar registro </button>
            </fieldset>  
            
            <hr />
            
            <div>
                
                <h1> Trabajos realizados Nro orden - XX </h1>       
                
                <fieldset>	

                    <div id="imprimir">
                                 
                        <div class="datagrid" style="margin-top: 30px;">
                            <table> 
                                <thead style="text-align: center;">
                                    <tr>
                                        <th> Fecha </th> <th> Seccion </th> <th> Detalle </th> <th> </th> 
                                    </tr>
                                </thead>
                                <tbody id="catalogos"></tbody>
                                <tfoot>
                                    <tr> <td colspan="4"> <div id="paging"> <br /> </div> </td> </tr>
                                </tfoot>                                
                            </table> 
                        </div>
                    
                    </div>    
                        
                </fieldset>	
                
            </div>            
            
        </div>        
        
    </body>
	
</html>