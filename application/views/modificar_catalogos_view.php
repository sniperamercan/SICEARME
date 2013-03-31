<!DOCTYPE html>
<html lang="es">
    
    <head>
        
        <script type="text/javascript">

            $(document).ready(function() {
                armoFormulario();
                $("#fabricacion").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("#vencimiento").datepicker({ dateFormat: "yy-mm-dd", monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"], dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], changeYear: true, changeMonth: true, dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"], monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] } );
                $("input:submit").button();
                $("button").button(); 
                $("input:button").button(); 
            });	

            function armoFormulario() {
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>modificar_catalogos/armoFormulario",
                    success: function(data){
                        if(data[0] == 1) {
                            
                            $("#tipo_arma").attr("disabled", true); 
                            $("#marca").attr("disabled", true); 
                            $("#calibre").attr("disabled", true); 
                            $("#modelo").attr("disabled", true); 
                            $("#sistema").attr("disabled", true); 
                            
                            $("#tipo_arma").html(data[1]);
                            $("#marca").html(data[2]);
                            $("#calibre").html(data[3]);
                            $("#modelo").html(data[4]);
                            $("#sistema").html(data[5]);
                        }else{
                            $("#tipo_arma").html(data[1]);
                            $("#marca").html(data[2]);
                            $("#calibre").html(data[3]);
                            $("#modelo").html(data[4]);
                            $("#sistema").html(data[5]);                          
                        }                            
                  }
                });                
            }
            
            function modificarCatalogo() {
                
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
                    url: "<?php base_url(); ?>modificar_catalogos/validarDatos",
                    data: "tipo_arma="+tipo_arma+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&sistema="+sistema+"&empresa="+empresa+"&pais_empresa="+pais_empresa+"&fabricacion="+fabricacion+"&vencimiento="+vencimiento,
                    success: function(data){
                        if(data[0] == "1"){            
                            jAlert("Catalogo modificado con exito - Nro de catalogo = "+data[1], "Correcto", function() { irAFrame('<?php echo base_url('mb_catalogos'); ?>','O.C.I >> Modificar/Anular >> Catalogos');  });
                        }else{
                            jAlert(data[0], "Error");
                        }                            
                  }
                });               
            }
            
            function volverListado() {
                irAFrame('<?php echo base_url('mb_catalogos'); ?>','O.C.I >> Modificar/Anular >> Catalogos');
            }            
            
            //INICIO llamadas a crear los tipos
            
            function crearTipoArma() {
                $.colorbox({href:"<?php echo base_url('alta_tipo_arma'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA TIPO ARMA", onClosed: function(){ cargoTiposArmas(); } });
            }
            
            function crearMarca() {
                $.colorbox({href:"<?php echo base_url('alta_marca'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA MARCA", onClosed: function(){ cargoMarcas(); } });
            }

            function crearCalibre() {
                $.colorbox({href:"<?php echo base_url('alta_calibre'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA CALIBRE", onClosed: function(){ cargoCalibres(); } });
            }
            
            function crearModelo() {
                $.colorbox({href:"<?php echo base_url('alta_modelo'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA MODELO", onClosed: function(){ cargoModelos(); } });
            }

            function crearSistema() {
                $.colorbox({href:"<?php echo base_url('alta_sistema'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA SISTEMA", onClosed: function(){ cargoSistemas(); } });
            }
            
            function crearEmpresa() {
                $.colorbox({href:"<?php echo base_url('alta_empresa'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:200, title:"ALTA EMPRESA", onClosed: function(){ cargoEmpresas(); } });
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

            <h1> Modificar catalogos </h1>    
            
            <fieldset>	
                
                <dl> 		
                <dt><label for="tipo_arma"> Tipo arma </label></dt>	
                <dd><select id="tipo_arma"> </select> <img style="cursor: pointer;" onclick="crearTipoArma();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="marca"> Marca </label></dt>	
                <dd><select id="marca"> </select> <img style="cursor: pointer;" onclick="crearMarca();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="calibre"> Calibre </label></dt>	
                <dd><select id="calibre"> </select> <img style="cursor: pointer;" onclick="crearCalibre();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="modelo"> Modelo </label></dt>	
                <dd><select id="modelo"> </select> <img style="cursor: pointer;" onclick="crearModelo();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl> 		
                <dt><label for="sistema"> Sistema </label></dt>	
                <dd><select id="sistema"> </select> <img style="cursor: pointer;" onclick="crearSistema();" src="<?php echo base_url(); ?>images/sumar.png" /></dd> 					
                </dl>
                
                <dl>
                <dt><label for="empresa"> Empresa </label></dt>
                <dd><select id="empresa"> <?php echo $empresas; ?> </select> <img style="cursor: pointer;" onclick="crearEmpresa();" src="<?php echo base_url(); ?>images/sumar.png" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="pais_empresa"> Pais empresa </label></dt>
                <dd><select id="pais_empresa"> <?php echo $paises ?> </select></dd>
                </dl>                 

                <dl>
                <dt><label for="fabricacion"> Año Fabricacion </label></dt>
                <dd><input readonly="readonly" type="text" id="fabricacion" class="text" value="<?php echo $fabricacion; ?>" /></dd>
                </dl>
                
                <dl>
                <dt><label for="vencimiento"> Vencimiento </label></dt>
                <dd><input readonly="readonly" type="text" id="vencimiento" class="text" value="<?php echo $vencimiento; ?>" /></dd>
                </dl>  
                
            </fieldset>	

            <fieldset class="action">	
                <button style="margin-right: 20px;" onclick="modificarCatalogo();"> Modificar catalogo </button> <button style="margin-right: 20px;" onclick="volverListado();"> Volver al listado de catalogos </button>
            </fieldset>  
            
        </div>        
        
    </body>
	
</html>