<!DOCTYPE html>
<html lang="es">

    <head>
        
        <title>SICEARME</title>	
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        	
        <!-- SCRIPT Plugins -->
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-1.8.2.min.js');?>' ></script>	
        <script type="text/javascript" src='<?php echo base_url('js/jquery-ui/js/jquery-ui-1.8.23.custom.min.js');?>' ></script>	
        <script type="text/javascript" src='<?php echo base_url('js/colorbox/colorbox/jquery.colorbox-min.js'); ?>'></script>
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
                        'speed': 400,
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
                        <img style="cursor: pointer;" onclick="cerrarSesion();" src="<?php echo base_url(); ?>images/exit.png" />
                        <img style="cursor: pointer;" onclick="irAFrame('<?php echo base_url('correo'); ?>','Correo');" id="correo" src="<?php echo base_url(); ?>images/sobre_vacio.png" />
                        <a onclick='informacionUsuario();' style='cursor: pointer'> <img title='usuario' src='<?php echo base_url('images/user_32.gif'); ?>'/></a><label> <?php echo base64_decode($_SESSION['usuario']); ?> </label>	
                    </td>  

                    <td style="border: none; width: 30%" align="right">
                        <div class="dock" id="dock">
                            <div class="dock-container">
                                  <?php if($this->perms->verificoPerfil1()) { ?><a class="dock-item" onclick='$.colorbox({href:"<?php echo base_url('agregar_usuario'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:400, title:"AGREGAR USUARIO"});'><img src='<?php echo base_url('images/user_add.png'); ?>' alt="Agregar usuario" /><span>Agregar usuario</span></a><?php } ?>
                                  <a class="dock-item" onclick='window.open ("js/calculadora/calculadora.html", "mywindow","toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=206,height=200");'><img src='<?php echo base_url('images/calc.png'); ?>' alt="Calculadora" /><span>Calculadora</span></a> 
                                  <a class="dock-item" onclick='$.colorbox({href:"<?php echo base_url('cambiar_clave'); ?>", top:true, iframe:false, innerWidth:800, innerHeight:300, title:"CAMBIAR CLAVE"});'><img src='<?php echo base_url('images/key.png'); ?>' alt="Cambiar clave" /><span>Cambiar clave</span></a>                               			 
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

                        <li><a href="#"> O.C.I </a>
                            <ul>
                                
                                <?php if($this->perms->verificoPerfil2() || $this->perms->verificoPerfil3()) { ?>
                                
                                    <li><a href="#"> Ingresos </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('compras'); ?>','O.C.I >> Ingresos >> Compras');"> Compras </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('catalogos'); ?>','O.C.I >> Ingresos >> Catalogos');"> Catalogos </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('fichas'); ?>','O.C.I >> Ingresos >> Fichas');"> Fichas </a></li>
                                        </ul>
                                    </li> 
                                
                                <?php } ?>   
                                
                                <?php if($this->perms->verificoPerfil3()) { ?>    
                                    
                                    <li><a href="#"> Mantenimientos </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mantenimiento_compras'); ?>','O.C.I >> Mantenimientos >> Compras');"> Compras </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mantenimiento_catalogos'); ?>','O.C.I >> Mantenimientos >> Catalogos');"> Catalogos </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mantenimiento_fichas'); ?>','O.C.I >> Mantenimientos >> Fichas');"> Fichas </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                
                                <?php if($this->perms->verificoPerfil2() || $this->perms->verificoPerfil3()) { ?>
                                
                                    <li><a href="#"> Consultas </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_compras'); ?>','O.C.I >> Consultas >> Compras');"> Compras </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_catalogos'); ?>','O.C.I >> Consultas >> Catalogos');"> Catalogos </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_fichas'); ?>','O.C.I >> Consultas >> Fichas');"> Fichas </a></li>
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
                                
                                    <li><a href="#"> Ingresos </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('actas_alta'); ?>','Abastecimiento >> Ingresos >> Actas alta');"> Actas alta </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('actas_baja'); ?>','Abastecimiento >> Ingresos >> Actas baja');"> Actas Baja </a></li>
                                        </ul>
                                    </li> 
                                
                                <?php } ?>   
                                
                                <?php if($this->perms->verificoPerfil5()) { ?>    
                                    
                                    <li><a href="#"> Mantenimientos </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mantenimiento_actas_alta'); ?>','Abastecimiento >> Mantenimientos >> Actas altas');"> Actas altas </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mantenimiento_actas_baja'); ?>','Abastecimiento >> Mantenimientos >> Actas bajas');"> Actas bajas </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                
                                <?php if($this->perms->verificoPerfil4() || $this->perms->verificoPerfil5()) { ?>
                                
                                    <li><a href="#"> Consultas </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_actas_alta'); ?>','Abastecimiento >> Consultas >> Actas altas');"> Actas altas </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_actas_baja'); ?>','Abastecimiento >> Consultas >> Actas bajas');"> Actas bajas </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_historial_arma'); ?>','Abastecimiento >> Consultas >> Historial de arma');"> Historial de arma </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                
                            </ul>
                        </li>  

                    <?php } ?>  
                        
                    <!-- FIN ABASTECIMIENTO -->    
                        
                    <!-- INICIO RESERVA -->
                    
                    <?php if($this->perms->verificoPerfil8()) { ?>

                        <li><a href="#"> Reserva </a>
                            <ul>
                                
                                <li><a href="#"> Ingresos </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('inventario_reserva'); ?>','Reserva >> Ingresos >> Inventario reserva');"> Inventario reserva </a></li>
                                    </ul>
                                </li> 

                                <li><a href="#"> Mantenimientos </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('mantenimiento_inventario_reserva'); ?>','Reserva >> Mantenimientos >> Inventario reserva');"> Inventario reserva </a></li>
                                    </ul>
                                </li>

                                <li><a href="#"> Consultas </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_inventario_reserva'); ?>','Reserva >> Consultas >> Inventario reserva');"> Inventario reserva </a></li>
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
                                
                                    <li><a href="#"> Ingresos </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('orden_trabajo'); ?>','Taller armamento >> Ingresos >> Orden de trabajo');"> Orden de trabajo </a></li>
                                        </ul>
                                    </li> 
                                
                                <?php } ?>   
                                
                                <?php if($this->perms->verificoPerfil7()) { ?>    
                                    
                                    <li><a href="#"> Mantenimientos </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mantenimiento_orden_trabajo'); ?>','Taller armamento >> Mantenimientos >> Orden de trabajo');"> Orden de trabajo </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('mantenimiento_cambios_ficha'); ?>','Taller armamento >> Mantenimientos >> Cambios ficha de armamento');"> Cambios ficha de armamento </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                
                                <?php if($this->perms->verificoPerfil6() || $this->perms->verificoPerfil7()) { ?>
                                
                                    <li><a href="#"> Consultas </a>
                                        <ul>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_orden_trabajo'); ?>','Taller armamento >> Consultas >> Orden de trabajo');"> Orden de trabajo </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_estado_armamento'); ?>','Taller armamento >> Consultas >> Estado del armamento');"> Estado del armamento </a></li>
                                            <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_historial_reparacion'); ?>','Taller armamento >> Consultas >> Historial de reparacion');"> Historial de reparacion </a></li>
                                        </ul>
                                    </li>
                                
                                <?php } ?> 
                                
                            </ul>
                        </li>  

                    <?php } ?> 
                        
                    <!-- FIN TALLER DE ARMAMENTO -->
                    
                    <!-- INICIO ALMACEN -->
                        
                    <?php if($this->perms->verificoPerfil9()) { ?>

                        <li><a href="#"> Almacen </a>
                            <ul>
                                
                                <li><a href="#"> Ingresos </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('alta_repuestos'); ?>','Almacen >> Ingersos >> Alta de respuestos');"> Alta de respuestos </a></li>
                                    </ul>
                                </li> 

                                <li><a href="#"> Mantenimientos </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('baja_repuestos'); ?>','Almacen >> Mantenimientos >> Baja de respuestos');"> Baja de respuestos </a></li>
                                    </ul>
                                </li>

                                <li><a href="#"> Consultas </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_repuestos'); ?>','Almacen >> Consultas >> Respuestos');"> Respuestos </a></li>
                                    </ul>
                                </li>
                                
                            </ul>
                        </li>  

                    <?php } ?>  
                        
                    <!-- FIN ALMACEN -->
                    
                    <!-- INICIO CONSULTAS -->
                        
                    <?php if($this->perms->verificoPerfil10()) { ?>

                        <li><a href="#"> Consultas </a>
                            <ul>
                                
                            </ul>
                        </li>  

                    <?php } ?>  
                        
                    <!-- FIN CONSULTAS -->    
                        
                    <!-- INICIO ADMINISTRACION -->
                    
                    <?php if($this->perms->verificoPerfil1()) { ?>

                        <li><a href="#"> Administracion </a>
                            <ul>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('agregar_usuario'); ?>','Adminitracion >> Agregar usuarios');"> Agregar usuario </a></li>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('gestionar_usuarios'); ?>','Administracion >> Gestion de usuario');"> Gestion de usuario </a></li>
                                <li><a href="#" onclick="irAFrame('<?php echo base_url('consulta_logs_ingresos'); ?>','Administracion >> Ver logs ingresos');"> Ver logs ingresos </a></li>
                                <li><a href="#"> Desarrollo </a>
                                    <ul>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('desarrollo_descripcion_actualizacion_sistema'); ?>','Administracion >> Desarrollo >> Descripcion actualizacion');"> Descripcion actualizacion </a></li>
                                        <li><a href="#" onclick="irAFrame('<?php echo base_url('desarrollo_incremento_version_sistema'); ?>','Adminitracion >> Desarrollo >> Incremento version');"> Incremento version </a></li>
                                        <li><a href="#" onclick="<?php $this->version->copiarVersion(); ?>"> Copiar version a servidor </a></li>
                                    </ul>
                                </li>                                
                            </ul>
                        </li>  

                    <?php } ?>
                        
                    <!-- FIN ADMINISTRACION -->    

                </ul>

            </div>			            
            
        </header>    
            
        <section id="cuerpo_pagina" style="margin-left: 50px; margin-right: 50px;">
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