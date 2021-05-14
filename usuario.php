<?php
    /*
     * Cupfsa Coins - Usuario
     * 
     */
    session_start();
    $pagina = "pg_usuario";

    ini_set( 'display_errors', 1 );
    include( "database/bd.php" );
    include( "database/data-acceso.php" );
    include( "database/data-usuarios.php" );
    include( "fn/fn-acceso.php" );
    include( "fn/fn-misc.php" );

    isAccesible( $pagina );
    $id_u = NULL;
    if( isset( $_GET["id"] ) && ( is_numeric( $_GET["id"] ) ) ){
    	$id_u = $_GET["id"];
    	$usuario = obtenerDataUsuarioPorId( $dbh, $id_u );
    	$lnk_login = "https://coins.cupfsa.com/index.php?email=$usuario[email]&token=$usuario[token_ingreso]";
    	if( $usuario ){
	    	$roles_u = rolesUsuario( $dbh, $id_u );
    	}else 
    		header( "Location: usuarios.php" );
    } else $usuario = NULL;
?>
<!doctype html>
<html class="fixed">
	<head>
		<!-- Basic -->
		<meta charset="UTF-8">

		<title><?php echo $usuario["nombre"]; ?> :: Cupfsa Coins</title>
		<meta name="keywords" content="CUPFSA Coins" />
		<meta name="description" content="CUPFSA Coins ficha de usuario">
		<meta name="author" content="mikeven@gmail.com">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

		<link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		
		<link rel="stylesheet" href="assets/vendor/pnotify/pnotify.custom.css" />
		
		<link rel="stylesheet" href="assets/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
		<link rel="stylesheet" href="assets/vendor/summernote/summernote.css" />
		<link rel="stylesheet" href="assets/vendor/summernote/summernote-bs3.css" />
		<link rel="stylesheet" href="assets/vendor/codemirror/lib/codemirror.css" />
		<link rel="stylesheet" href="assets/vendor/codemirror/theme/monokai.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body>
		<section class="body">
			<!-- start: header -->
			<?php include( "sections/header.php" );?>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php include( "sections/left-sidebar.php" );?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2><i class="fa fa-user" aria-hidden="true"></i> Datos de usuario</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.html">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span><a href="usuarios.php">Usuarios</a></span></li>
								<li><span><?php echo $usuario["nombre"]; ?></span></li>
							</ol>
					
							<a class="sidebar-right-null" data-open=""></a>
						</div>
					</header>
					
					<!-- start: page -->
						<div class="row">
							<div class="col-sm-8 col-xs-12">
							<?php if( $usuario ){ ?>
							<section class="panel">
								
								<div class="panel-body">
									
									<div class="col-sm-12 col-xs-12">
										<div class="form-group">
											<input type="hidden" id="idproducto" 
											value="<?php echo $usuario["idUSUARIO"]; ?>">
											<h4></h4>
										</div>
										
										<section class="panel panel-horizontal">
											<header class="panel-heading bg-white">
												<div class="panel-heading-icon bg-dark mt-sm">
													<i class="fa fa-user"></i>
												</div>
											</header>
											<div class="panel-body p-lg">
												<h3 class="text-semibold mt-sm">
													<?php echo $usuario["nombre"]." ".$usuario["apellido"]; ?>
												</h3>
												<p><b>Email:</b> <?php echo $usuario["email"]; ?></p>
												<p><b>Departamento: </b> <?php echo $usuario["departamento"]; ?></p>
												<p><b>Fecha creación:</b> <?php echo $usuario["fregistro"]; ?></p>
												<p><b>
													Roles:</b> 	<?php  foreach ( $roles_u as $rl ){ ?>
																<?php echo $rl["nombre"].'&nbsp&nbsp&nbsp'; ?>
															<?php  } ?>
												</p>
												<!--
												<p><b>Enlace de ingreso:</b></p>
												<code><?php echo $lnk_login; ?></code> -->
											</div>
											<div id="bot_enl_ing" class="hidden">
												<a href="#!">
												<button id="btn_enviar_lnk" type="button" 
												data-u="<?php echo $usuario["idUSUARIO"]; ?>" 
												class="mb-xs mt-xs mr-xs btn btn-primary adminev">
													<i class="fa fa-link"></i> Enviar enlace de ingreso a usuario</button>
												</a>
											</div>
										</section>
										
									</div>
								</div>
								<footer class="panel-footer">
									<div class="row">
										
										<div class="col-sm-12" align="right">
											<a href="usuarios.php">Regresar</a>
										</div>
										
									</div>
								</footer>
							</section>
							<?php } else { ?>
								<h4>No existe registro</h4>
							<?php } ?>	
							</div>	
						</div>
						
					<!-- end: page -->
				</section>
			</div>

		</section>

		<?php include( "sections/modals/confirmar-accion.html" ); ?>

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
	
		<script src="assets/vendor/bootstrap-markdown/js/markdown.js"></script>
		<script src="assets/vendor/bootstrap-markdown/js/to-markdown.js"></script>
		<script src="assets/vendor/bootstrap-markdown/js/bootstrap-markdown.js"></script>
		<script src="assets/vendor/codemirror/lib/codemirror.js"></script>
		<script src="assets/vendor/codemirror/addon/selection/active-line.js"></script>
		<script src="assets/vendor/codemirror/addon/edit/matchbrackets.js"></script>
		<script src="assets/vendor/codemirror/mode/javascript/javascript.js"></script>
		<script src="assets/vendor/codemirror/mode/xml/xml.js"></script>
		<script src="assets/vendor/codemirror/mode/htmlmixed/htmlmixed.js"></script>
		<script src="assets/vendor/codemirror/mode/css/css.js"></script>
		<script src="assets/vendor/summernote/summernote.js"></script>
		<script src="assets/vendor/bootstrap-maxlength/bootstrap-maxlength.js"></script>
		<script src="assets/vendor/ios7-switch/ios7-switch.js"></script>
		<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>
		<script src="assets/vendor/pnotify/pnotify.custom.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>
		<script src="js/init.modals.js"></script>

		<!-- Custom scripts -->
		<script src="js/fn-ui.js"></script>		
		<script src="js/fn-usuarios.js"></script>

	</body>
</html>