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
    
                var fecha         = $("#fecha").val();
                var unidad        = $("#unidad").val();
                var nro_serie     = $("#nro_serie").val();
                var marca         = $("#marca").val();
                var calibre       = $("#calibre").val();
                var modelo        = $("#modelo").val();
                var observaciones = $("#observaciones").val();
 
                $.ajax({
                    type: "post",  
                    dataType: "json",
                    url: "<?php base_url(); ?>alta_ordenes_trabajo/validarDatos",
                    data: "fecha="+fecha+"&unidad="+unidad+"&nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo+"&observaciones="+observaciones,
                    success: function(data){
                        if(data[0] == "1"){            
                            jAlert("Orden de trabajo generada con exito - <b> Nro de orden generado = "+data[1]+" </b>", "Correcto", function() { irAFrame('<?php echo base_url('alta_ordenes_trabajo'); ?>','Taller armamento >> Alta >> Ordenes de trabajo'); });
                        }else{
                            jAlert(data[0], "Error");
                        }                            
                    }
                });               
            }
            
            function busquedaFichas() {
                $.colorbox({href:"<?php echo base_url('busqueda_fichas_taller'); ?>", top:false, iframe:false, innerWidth:900, innerHeight:700, title:"BUSQUEDA FICHAS", onClosed: function(){ cargoFichasFiltro(); } });
            }
            
            function cargoFichasFiltro() {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoFichasFiltro",
                   success: function(data) {
                       $("#nro_serie").html("");
                       $("#nro_serie").html(data[0]);
                       $("#marca").html("");
                       $("#marca").html(data[1]);
                       $("#calibre").html("");
                       $("#calibre").html(data[2]);
                       $("#modelo").html("");
                       $("#modelo").html(data[3]);
                       $("#tipo_arma").val("");
                       $("#sistema").val("");
                       $("#tipo_arma").val(data[4]);
                       $("#sistema").val(data[5]);                       
                   }
                });                
            }            
            
            function cargoMarcas(nro_serie) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoMarcas",
                   data: "nro_serie="+nro_serie,
                   success: function(data) {
                       $("#marca").html("");
                       $("#marca").html(data);
                   }
                });                
            }
            
            function cargoCalibres(nro_serie, marca) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoCalibres",
                   data: "nro_serie="+nro_serie+"&marca="+marca,
                   success: function(data) {
                       $("#calibre").html("");
                       $("#calibre").html(data);
                   }
                });                
            }
            
            function cargoModelos(nro_serie, marca, calibre) {
                $.ajax({
                   type: "post",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoModelos",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre,
                   success: function(data) {
                       $("#modelo").html("");
                       $("#modelo").html(data);
                   }
                });                
            }
            
            function cargoDatos(nro_serie, marca, calibre, modelo) {
                $.ajax({
                   type: "post",
                   dataType: "json",
                   url: "<?php base_url(); ?>alta_ordenes_trabajo/cargoDatos",
                   data: "nro_serie="+nro_serie+"&marca="+marca+"&calibre="+calibre+"&modelo="+modelo,
                   success: function(data) {
                       $("#tipo_arma").val("");
                       $("#sistema").val("");
                       $("#tipo_arma").val(data[0]);
                       $("#sistema").val(data[1]);
                   }
                });                 
            }
            
            
        </script>
        
    </head>

    <body class="cuerpo">

        <div>			

            <h1> Alta de ordenes de trabajo </h1>    
            
            <fieldset>	
                
                <dl>
                <dt><label for="fecha"> Fecha </label></dt>
                <dd><input readonly="readonly" type="text" id="fecha" class="text" /></dd>
                </dl>                
                
                <dl>
                <dt><label for="unidad"> Unidad </label></dt>
                <dd><select id="unidad"> <?php echo $unidades; ?> </select></dd>
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
                <dd><select id="modelo" onchange="cargoDatos($('#nro_serie').val(), $('#marca').val(), $('#calibre').val(), this.value);"> </select></dd> 					
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