<!DOCTYPE html>
<html lang="es">

    <head>
        
        <title>SICEARME</title>	
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        	
        <!-- SCRIPT Plugins -->
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-1.8.2.min.js');?>' ></script>	
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-ui-1.8.23.custom.min.js');?>' ></script>	
        <script type="text/javascript" src='<?php echo base_url('js/colorbox/colorbox/jquery.colorbox.js'); ?>'></script>
        <script type="text/javascript" src='<?php echo base_url('js/print/jquery.printElement.js'); ?>'></script>
        <script type="text/javascript" src='<?php echo base_url('js/jquery.alerts-1.1/jquery.alerts.js'); ?>'></script>
        <script type="text/javascript" src='<?php echo base_url('js/smartupdater/smartupdater.4.0.js'); ?>'></script>
        
        <!-- CSS Plugins -->
        <link media="screen" rel="stylesheet" href='<?php echo base_url('js/jquery.alerts-1.1/jquery.alerts.css'); ?>' />
        <link media="screen" rel="stylesheet" href='<?php echo base_url('js/colorbox/example1/colorbox.css'); ?>' />	
        <link rel="stylesheet" href='<?php echo base_url('js/jquery-ui/css/black-tie/jquery-ui-1.8.23.custom.css'); ?>' type="text/css" />
        <link rel="stylesheet" href='<?php echo base_url('css/estilo.css'); ?>' type="text/css" />
        <link rel="shortcut icon" href='<?php echo base_url('css/template/favicon.png');?>' />
        
        <!-- Solo para menu principal -->
        <link rel="stylesheet" href='<?php echo base_url('js/amenu/amenu.css'); ?>' />
        <script src='<?php echo base_url('js/amenu/amenu.js'); ?>'></script>	        
        <script type="text/javascript" src='<?php echo base_url('js/css-dock-menu/js/interface.js'); ?>'></script>
        <link href='<?php echo base_url('js/css-dock-menu/style.css'); ?>' rel="stylesheet" type="text/css" />

        <script type="text/javascript">

            $(document).ready(function() {
                    
                //click derecho off
                $(document).bind("contextmenu",function(e){
                    return false;
                });

                $("input:submit").button();
                $("button").button(); 
                $("input:button").button();    

                $('input:text').attr('maxLength','100'); //seteo el maximo de caracteres a 60
                $('textarea').attr('maxLength','1000'); //seteo el maximo de caracteres a 1000                

                <?php echo $irAFrame; ?>    

                $("#dock").Fisheye(
                                {
                                    maxWidth: 50,
                                    items: 'a',
                                    itemsText: 'span',
                                    container: '.dock-container',
                                    itemWidth: 40,
                                    proximity: 90,
                                    halign: 'right'
                                }
                );

                $('#amenu-list').amenu({
                        'animation': 'none'       //  show, fade, slide, wind, none
                });
                
                $("#correo").smartupdater({
                    type: 'post',
                    url: '<?php echo base_url(); ?>panelprincipal/verificarCorreo',
                    minTimeout: 60000 // 60 seconds
                    }, function (data) {
                            if(data == 1){
                                $("#correo").attr("src", "<?php echo base_url(); ?>/images/sobre_notificacion.png");
                            }else {
                                $("#correo").attr("src", "<?php echo base_url(); ?>/images/sobre_vacio.png");
                            }
                       }
                );
                
            });	

            function irAFrame(src, navigation){			
                      
                $.ajax({
                   type: 'post',
                   url: src,
                   success: function (data){
                       $('#contenido').html(data);
                       $('#navegacion1').html(navigation); 
                       verificarCorreo();
                       
                       //cerrar el amenu (compatibilidad IPAD/IPHONE)
                       $('#amenu-list').find('a').removeClass('active');
                       $('#amenu-list').find('ul').stop(true,true).css('display','none');
                       
                       //alert($("body").height());

                       //go to top   
                       if( $("body").height() >= 790 ) { 
                           $("#top").show();
                       }else {
                           $("#top").hide();
                       }
                       //end go to top                         
                   }
                });
            }

            function verificarCorreo() {
                
                $.ajax({
                   type: 'post',
                   url: '<?php echo base_url(); ?>panelprincipal/verificarCorreo',
                   success: function (data){
                        if(data == 1){
                            $("#correo").attr("src", "<?php echo base_url(); ?>/images/sobre_notificacion.png");
                        }else {
                            $("#correo").attr("src", "<?php echo base_url(); ?>/images/sobre_vacio.png");
                        }
                   }
                });
                
            }

            function informacionUsuario(){
                $.ajax({
                        type: 'post',					
                        url: '<?php echo base_url(); ?>panelprincipal/informacionUsuario',
                        success: function (datos){
                                jAlert(datos, "");
                        }
                });
            }
            
            function abrirManual() {
                window.open ("<?php echo base_url(); ?>manual/manual.pdf", "mywindow","toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=0");
            }

            function destruyoSession(){
                $.ajax({
                        url: '<?php echo base_url(); ?>panelprincipal/destruyoSession',
                        async: false
                });				
            }

            function cerrarSesion(){
                $.ajax({
                        url: '<?php echo base_url(); ?>panelprincipal/destruyoSession',
                        async: false,
                        success: function (){
                                window.location.href='<?php echo base_url(); ?>login'
                        }
                });				
            }

            function goTop() {
                //$('body, html').animate({ scrollTop: 0 }, 'slow');
                $('body, html').scrollTop(0); //todos los navegadores
            }

            //$(window).unload( function () { destruyoSession(); } );

        </script>	

    </head>

    <body class="cuerpo" style="background-color: #E6E6E6;">
		
        <header>
        
            <table>
                <tr> <td> </td> </tr>	
            </table>

            <table border="0" width="100%">

                <tr>
                    <td style="border: none; width: 70%;" align="left">	
                        <img style="margin-right: 20px;" src="<?php echo base_url(); ?>images/menu.png" />
                        <img title="Correo" style="cursor: pointer;" onclick="irAFrame('<?php echo base_url('correo'); ?>','Correo');" id="correo" src="<?php echo base_url(); ?>images/sobre_vacio.png" />
                    </td>  

                    <td style="border: none; width: 30%" align="right">
                        <div>
                            <div>
                              <a onclick='abrirManual();' style='cursor: pointer;'> <img title='Manual de usuario' src='<?php echo base_url('images/manual.png'); ?>'/></a>
                              <a onclick='$.colorbox({href:"<?php echo base_url('modificar_clave'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:300, title:"MODIFICAR CLAVE"});'><img style='cursor: pointer;' src='<?php echo base_url('images/key.png'); ?>' title="Modificar clave" /></a>    
                              <a onclick='informacionUsuario();' style='cursor: pointer;'> <img title='Información usuario' src='<?php echo base_url('images/user.png'); ?>'/></a>
                              <a onclick='cerrarSesion();'><img style='cursor: pointer;' title='Salir del sistema' style="cursor: pointer;" src="<?php echo base_url(); ?>images/exit.png" /></a>                                     
                            </div>
                        </div> 				

                    </td>
                </tr>	

            </table>
	
            <div id="amenu-wrapper">       		     	  

                <ul id="amenu-list">                  		

                    <li><a href="#" onclick="irAFrame('<?php echo base_url('resumen'); ?>','Inicio >> Resumen');"> Inicio </a> </li>
                    
                    <!-- INICIO O.C.I -->
                    
                    <?php if($this->perms->verificoPerfil2() || $this->perms->verificoPerfil3()) { ?>

                        <li><a href="#"> O.C.I. </a>
                            <ul>
                                
                                <?php if($this->perms->verificoPerfil2() || $this->perms->verificoPerfil3()) { ?>
                                
                                    <li><a href="#"> Altas </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_compras'); ?>','O.C.I. >> Alta >> Compras');"> Compras </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_catalogos'); ?>','O.C.I. >> Alta >> Catálogos');"> Catálogos </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('upload'); ?>','O.C.I. >> Cargo documentos / imágenes');"> Cargo documentos / imágenes </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_fichas'); ?>','O.C.I. >> Alta >> Fichas');"> Fichas </a></li>
                                        </ul>
                                    </li> 
                                
                                <?php } ?>   
                                
                                <?php if($this->perms->verificoPerfil3()) { ?>    
                                    
                                    <li><a href="#"> Modificar/Anular </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_compras'); ?>','O.C.I. >> Modificar/Anular >> Compras');"> Compras </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_catalogos'); ?>','O.C.I. >> Modificar/Anular >> Catálogos');"> Catálogos </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_fichas'); ?>','O.C.I. >> Modificar/Anular >> Fichas');"> Fichas </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                
                                <?php if($this->perms->verificoPerfil2() || $this->perms->verificoPerfil3()) { ?>
                                
                                    <li><a href="#"> Listado </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_compras'); ?>','O.C.I. >> Listado >> Compras');"> Compras </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_catalogos'); ?>','O.C.I. >> Listado >> Catálogos');"> Catálogos </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_fichas'); ?>','O.C.I. >> Listado >> Fichas');"> Fichas </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_documentos_imagenes_catalogos'); ?>','O.C.I. >> Listado >> Documentos / imágenes (catálogos)');"> Documentos / imágenes (catálogos) </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                    
                                <?php if($this->perms->verificoPerfil2() || $this->perms->verificoPerfil3()) { ?>
                                
                                    <li><a href="#"> Consulta </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_stock_total_armas_detallado'); ?>','O.C.I. >> Consulta >> Stock total armas detallado');"> Stock total armas detallado </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_stock_total_armas_resumido'); ?>','O.C.I. >> Consulta >> Stock total armas resumido');"> Stock total armas resumido </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_historial_movimiento_ficha'); ?>','O.C.I. >> Consulta >> Historial movimiento ficha');"> Historial movimiento ficha </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?>                                     
                                
                            </ul>
                        </li>  

                    <?php } ?>  
                        
                    <!-- FIN O.C.I -->   
                    
                    <!-- INICIO ABASTECIMIENTO -->
                        
                    <?php if($this->perms->verificoPerfil4() || $this->perms->verificoPerfil5()) { ?>

                        <li><a href="#"> Abastecimiento </a>
                            <ul>
                                
                                <?php if($this->perms->verificoPerfil4() || $this->perms->verificoPerfil5()) { ?>
                                
                                    <li><a href="#"> Altas </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_actas_alta'); ?>','Abastecimiento >> Altas >> Actas de alta');"> Actas de alta </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_actas_baja'); ?>','Abastecimiento >> Altas >> Actas de baja');"> Actas de baja </a></li>
                                        </ul>
                                    </li> 
                                
                                <?php } ?>   
                                
                                <?php if($this->perms->verificoPerfil5()) { ?>    
                                    
                                    <li><a href="#"> Modificar/Anular </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_actas_alta'); ?>','Abastecimiento >> Modificar/Anular >> Actas de alta');"> Actas de alta </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_actas_baja'); ?>','Abastecimiento >> Modificar/Anular >> Actas de baja');"> Actas de baja </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                
                                <?php if($this->perms->verificoPerfil4() || $this->perms->verificoPerfil5()) { ?>
                                
                                    <li><a href="#"> Listado </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_actas_alta'); ?>','Abastecimiento >> Listado >> Actas de alta');"> Actas de alta </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_actas_baja'); ?>','Abastecimiento >> Listado >> Actas de baja');"> Actas de baja </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                    
                                <li><a href="#"> Consulta </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_historial_movimiento_ficha'); ?>','Abastecimiento >> Consulta >> Historial movimiento ficha');"> Historial movimiento ficha </a></li>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_actas_unidad'); ?>','Abastecimiento >> Consulta >> Actas unidad');"> Actas unidad </a></li>
                                    </ul>
                                </li>                                    
                                
                            </ul>
                        </li>  

                    <?php } ?>  
                        
                    <!-- FIN ABASTECIMIENTO -->    
                        
                    <!-- INICIO RESERVA -->
                    
                    <?php if($this->perms->verificoPerfil8()) { ?>

                        <li><a href="#"> Reserva </a>
                            <ul>
                                <li><a href="#"> Modificar </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_inventario_reserva'); ?>','Reserva >> Modificar >> Inventario reserva');"> Inventario reserva </a></li>
                                    </ul>
                                </li>
                                
                                <li><a href="#"> Altas </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_actas_baja_reserva'); ?>','Reserva >> Altas >> Actas de baja');"> Actas de baja </a></li>
                                    </ul>
                                </li>                                 

                                <li><a href="#"> Listado </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_inventario_reserva'); ?>','Reserva >> Listado >> Inventario reserva');"> Inventario reserva </a></li>
                                    </ul>
                                </li>
                                
                                <li><a href="#"> Consulta </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_disponibilidad_reserva'); ?>','Reserva >> Consulta >> Disponibilidad armamento');"> Disponibilidad armamento </a></li>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_disponibilidad_tipo_arma_reserva'); ?>','Reserva >> Consulta >> Disponibilidad tipo de arma');"> Disponibilidad tipo de arma </a></li>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_disponibilidad_contenedor_reserva'); ?>','Reserva >> Consulta >> Disponibilidad contenedor');"> Disponibilidad contenedor </a></li>
                                    </ul>
                                </li>                                 
                                
                            </ul>
                        </li>  

                    <?php } ?>   
                        
                    <!-- FIN RESERVA -->
                    
                    <!-- INICIO TALLER DE ARMAMENTO -->
                        
                    <?php if($this->perms->verificoPerfil6() || $this->perms->verificoPerfil7()) { ?>

                        <li><a href="#"> Taller armamento </a>
                            <ul>
                                
                                <?php if($this->perms->verificoPerfil6() || $this->perms->verificoPerfil7()) { ?>
                                
                                    <li><a href="#"> Alta </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_ordenes_trabajo'); ?>','Taller armamento >> Alta >> Órdenes de trabajo');"> Órdenes de trabajo </a></li>
                                        </ul>
                                    </li> 
                                
                                <?php } ?>  
                                    
                                <?php if($this->perms->verificoPerfil6() || $this->perms->verificoPerfil7()) { ?>
                                
                                    <li><a href="#"> Acción </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('accion_ordenes_trabajo'); ?>','Taller armamento >> Acción >> Órdenes de trabajo');"> Órdenes de trabajo </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('accion_mutacion_armamento'); ?>','Taller armamento >> Acción >> Mutación de armamento');"> Mutación de armamento </a></li>
                                        </ul>
                                    </li> 
                                
                                <?php } ?>                                      
                                
                                <?php if($this->perms->verificoPerfil7()) { ?>    
                                    
                                    <li><a href="#"> Modificar/Anular </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_ordenes_trabajo'); ?>','Taller armamento >> Modificar/Anular >> Órdenes de trabajo');"> Órdenes de trabajo </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_acciones_ordenes_trabajo'); ?>','Taller armamento >> Modificar/Anular >> Acciones de una orden');"> Acciones de una orden </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                
                                <?php if($this->perms->verificoPerfil6() || $this->perms->verificoPerfil7()) { ?>
                                
                                    <li><a href="#"> Listado </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_ordenes_trabajo'); ?>','Taller armamento >> Listado >> Órdenes de trabajo');"> Órdenes de trabajo </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_acciones_ordenes_trabajo'); ?>','Taller armamento >> Listado >> Acciones de una orden');"> Acciones de una orden </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_cambios_piezas_asociadas'); ?>','Taller armamento >> Listado >> Cambios piezas asociadas');"> Cambios piezas asociadas </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_mutaciones_armamentos'); ?>','Taller armamento >> Listado >> Mutaciones de armamentos');"> Mutaciones de armamentos </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                    
                                <li><a href="#"> Consulta </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_estado_actual_arma'); ?>','Taller armamento >> Consulta >> Estado actual de arma');"> Estado actual arma </a></li>
                                    </ul>
                                </li>                                    
                                
                            </ul>
                        </li>  

                    <?php } ?> 
                        
                    <!-- FIN TALLER DE ARMAMENTO -->
                    
                    <!-- INICIO ALMACEN -->
                        
                    <?php if($this->perms->verificoPerfil9() || $this->perms->verificoPerfil10()) { ?>

                        <li><a href="#"> Almacén </a>
                            <ul>
                                
                                <li><a href="#"> Alta </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_stock_de_almacen'); ?>','Almacen >> Alta >> Respuestos');"> Repuestos </a></li>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_repuestos_nro_pieza'); ?>','Almacen >> Alta >> Piezas');"> Piezas </a></li>
                                    </ul>
                                </li> 

                                <?php if($this->perms->verificoPerfil10()) { ?>
                                
                                    <li><a href="#"> Modificar/Anular </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_stock_de_almacen'); ?>','Almacen >> Modificar/Anular >> Respuestos');"> Respuestos </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_repuestos_nro_pieza'); ?>','Almacen >> Modificar/Anular >> Piezas');"> Piezas </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 

                                <li><a href="#"> Listado </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_stock_almacen'); ?>','Almacen >> Listado >> Respuestos');"> Respuestos </a></li>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_repuestos_nro_pieza'); ?>','Almacen >> Listado >> Piezas');"> Piezas </a></li>
                                    </ul>
                                </li>
                                
                                <li><a href="#"> Consulta </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_movimientos_repuestos'); ?>','Almacen >> Consulta >> Movimientos repuestos');"> Movimientos repuestos </a></li>
                                    </ul>
                                </li>                                 
                                
                            </ul>
                        </li>  

                    <?php } ?>  
                        
                    <!-- FIN ALMACEN -->
                    
                    <!-- INICIO CONSULTAS -->
                        
                    <?php if($this->perms->verificoPerfil11()) { ?>

                        <li><a href="#"> Consultas </a>
                            <ul>
                                
                            </ul>
                        </li>  

                    <?php } ?>  
                        
                    <!-- FIN CONSULTAS -->    
                        
                    <!-- INICIO ADMINISTRACION -->
                    
                    <?php if($this->perms->verificoPerfil1()) { ?>

                        <li><a href="#"> Administración </a>
                            <ul>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_usuarios'); ?>','Administración >> Alta usuarios');"> Alta usuario </a></li>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('mb_usuarios'); ?>','Administración >> Modificar usuarios');"> Modificar usuarios </a></li>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_usuarios'); ?>','Administración >> Listado usuarios');"> Listado usuarios </a></li>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('listado_logs_ingresos'); ?>','Administración >> Registro de ingresos');"> Registro de ingresos </a></li>
                            </ul>
                        </li>  

                        <li><a href="#"> Desarrollo </a>
                            <ul>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('desarrollo_descripcion_actualizacion_sistema'); ?>','Desarrollo >> Descripción actualización');"> Descripción actualización </a></li>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('desarrollo_incremento_version_sistema'); ?>','Desarrollo >> Incremento versión');"> Incremento versión </a></li>
                            </ul>
                        </li>                         
                        
                    <?php } ?>
                        
                    <!-- FIN ADMINISTRACION -->    

                </ul>

            </div>			            
            
        </header>    
            
        <section id="cuerpo_pagina" style="margin-left: 10px; margin-right: 10px;">
            <br />				
            <label id="navegacion1" style="color: #151515; font-size: 12px; cursor: pointer; font-weight: bold; text-shadow: -2px 2px 3px #888; margin-left: 5px;"> </label>
            <hr /><br />            
            <article id="contenido"> </article>
        </section>
        
        <footer id="pie_pagina">
            <br /><hr />
            <div style="float: left;">
                <?php echo $info; ?>
                <br />
                <?php echo $version; ?>
                <br />
            </div>  

            <div style="float: right;">
                <p id="top" style="cursor: pointer;" onclick="goTop()"> <img style="opacity: 0.4" src="<?php echo base_url(); ?>images/top.png" /> </p>
            </div>
            
        </footer>
        
    </body>
	
</html>