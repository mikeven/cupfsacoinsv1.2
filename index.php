<?php
    /*
     * Cupfsa Coins - Ingreso
     * 
     */
    ini_set( 'display_errors', 1 );
    include( "database/bd.php" );
    include( "database/data-usuarios.php" );
?>
<!doctype html>
<html class="fixed">
	<head>
		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Cupfsa Coins</title>
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
		<style type="text/css">
			body {
				background: #222;
				width: 100%;
			}
			/*.alert{ display: none; margin-top: 20px; }*/
		</style>
	</head>
	<?php 
		$usuarios = obtenerUsuariosRegistrados( $dbh );
	?>
	<body style="background: #ecedf0">
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<a href="/" class="logo pull-left">
					<img src="assets/images/logo_cupsfa.png" height="60" alt="CUPFSA"/>
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Ingreso</h2>
					</div>
					<div class="panel-body">
						<form id="loginform">
							<div class="form-group mb-lg">
								<input name="login" type="hidden" value="1"/>
								<?php if( isset( $_GET["email"], $_GET["token"] ) ){ ?>
									<input name="token" type="hidden" value="<?php echo $_GET['token']?>"/>
								<?php } ?>
								<label class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									<?php if( isset( $_GET['email'] ) ){ ?>
										<input type="email" name="email" class="form-control" placeholder="Email" required 
									value="<?php echo $_GET['email'] ?>"/>
									<?php } else { ?>
										<input type="email" name="email" class="form-control" placeholder="Email" required/>
									<?php } ?>
								</div>
							</div>
							<div class="form-group mb-lg">
								<input name="login" type="hidden" value="1"/>
								<label class="col-sm-3 control-label">Contraseña</label>

								<div class="col-sm-9">
									<?php if( isset( $_GET['token'] ) ){ ?>
										<input type="password" name="password" class="form-control" 
										placeholder="Password" required value="<?php echo $_GET['token'] ?>"/>
									<?php } else { ?>
										<input type="password" name="password" class="form-control" 
										placeholder="Password" required/>
									<?php } ?>
								</div>

							</div>

							<div class="row">
								<div class="col-sm-8">
									
								</div>
								<div class="col-sm-4 text-right">
									<button type="button" class="btn btn-primary hidden-xs" onClick="log_in()">Ingresar</button>
									<button type="button" class="btn btn-primary btn-block btn-lg visible-xs mt-lg" onClick="log_in()">Ingresar</button>
								</div>
							</div>
							<div class="row" style="margin-top: 20px;">
								<div style="margin-top: 20px;" align="center">
									<a href="recuperar-password.php">
									<i class="fa fa-envelope"></i> Recuperar contraseña</a>
								</div>
							</div>
						</form>
					</div>
				</div>
				<p class="text-center text-muted mt-md mb-md">Cupfsa Coins 2021</p>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>
		<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>

		<script src="js/fn-acceso.js"></script>
		<script>
			<?php if( isset( $_GET["email"], $_GET["token"] ) ){ ?>
	        	$(document).ready(function () { log_in(); });
	    	<?php } ?>
	    </script>
	</body>
</html>