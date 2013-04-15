<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $("#fecha").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function altaOrdenTrabajo() {
                
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

            <h1> Alta registro ingreso a taller de armamento </h1>    
            
            <fieldset>	
                
                <dl>
                <dt><label for="fecha"> Fecha </label></dt>
                <dd><input readonly="readonly" type="text" id="fecha" class="text" /></dd>
                </dl>                
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <p class="subtituloform"> Datos del arma </p>
                
                <dl> 		
                <dt><label for="nro_serie"> Nro serie </label></dt>	
                <dd><select id="nro_serie" onchange="cargoMarcas(this.value);"> <?php echo $nro_series; ?> </select> <img style="cursor: pointer;" onclick="busquedaFichas();" src="<?php echo base_url(); ?>images/search.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="marca"> Marca </label></dt>	
                <dd><select id="marca" onchange="cargoCalibres($('#nro_serie').val(), this.value);"> </select></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="calibre"> Calibre </label></dt>	
                <dd><select id="calibre" onchange="cargoModelos($('#nro_serie').val(), $('#marca').val(), this.value);"> </select></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="modelo"> Modelo </label></dt>	
                <dd><select id="modelo"> </select></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="tipo_arma"> Tipo arma </label></dt>	
                <dd><input readonly="readonly" type="text" id="tipo_arma" class="txtautomatico" /></dd> 					
                </dl>
                
                <dl>
                <dt><label for="sistema"> Sistema </label></dt>
                <dd><input readonly="readonly" type="text" id="sistema" class="txtautomatico" /></dd>
                </dl>                
                
                <p><img src="<?php echo base_url() ?>images/barra.png" /></p>
                
                <dl>
                <dt><label for="unidad"> Unidad actual </label></dt>
                <dd><input readonly="readonly" type="text" id="unidad" class="txtautomatico" /></dd>
                </dl>    
                
                <dl> 		
                <dt><label for="observaciones"> Observaciones </label></dt>	
                <dd><textarea id="observaciones"> </textarea></dd> 					
                </dl>                
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="altaOrdenTrabajo();"> Alta orden de trabajo </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>