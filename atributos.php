<?php
    /*
     * Cupfsa Coins - Atributos
     * 
     */
    session_start();
    $pagina = "pg_atributos";
    ini_set( 'display_errors', 1 );
    include( "database/bd.php" );
    include( "database/data-acceso.php" );
    include( "database/data-atributos.php" );
    include( "database/data-usuarios.php" );
    include( "fn/fn-atributos.php" );
    include( "fn/fn-acceso.php" );

    isAccesible( $pagina );
?>
<!doctype html>
<html class="fixed">
	<head>
		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Atributos :: Cupfsa Coins</title>
		<meta name="keywords" content="CUPFSA Coins" />
		<meta name="description" content="CUPFSA Coins atributos de nominaciones">
		<meta name="author" content="mikeven@gmail.com">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
		<link rel="stylesheet" href="assets/vendor/pnotify/pnotify.custom.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />
		<link rel="stylesheet" href="assets/vendor/dropzone/css/basic.css" />
		<link rel="stylesheet" href="assets/vendor/dropzone/css/dropzone.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />

		<link rel="stylesheet" href="assets/vendor/dropzone/css/basic.css" />
		<link rel="stylesheet" href="assets/vendor/dropzone/css/dropzone.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
		<link rel="stylesheet" href="assets/vendor/summernote/summernote.css" />
		<link rel="stylesheet" href="assets/vendor/summernote/summernote-bs3.css" />
		<link rel="stylesheet" href="assets/vendor/codemirror/lib/codemirror.css" />
		<link rel="stylesheet" href="assets/vendor/codemirror/theme/monokai.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">
		<style> 
			#response{ float: right; }
			.dropzone {
			    min-height: 150px;
			}

			.dz-message{
				border: 2px dotted #CCC;
			}
		</style>

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<?php 
		$atributos = obtenerAtributosRegistrados( $dbh );
	?>
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
						<h2><i class="fa fa-flag"></i> Atributos</h2>
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Atributos</span></li>
							</ol>
							<a class="sidebar-right-null" data-open=""></a>
						</div>
					</header>

					<!-- start: page -->
					<div class="col-sm-5">
						<section class="panel">
							
								<header class="panel-heading">
									<h2 class="panel-title">Nuevo atributo</h2>
								</header>
								<div class="panel-body">
									<div class="panel-body">
										<form id="frm_natributo" class="form-horizontal">

											<div class="form-group">
												<input type="hidden" name="idusesion" value="<?php echo $accesos_usess["idUSUARIO"]?>">
												<label class="col-sm-4 control-label">Nombre <span class="required">*</span></label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-tag"></i>
														</span>
														<input type="text" name="nombre" 
														class="form-control" placeholder="Ej.: Responsabilidad" required/>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-4 control-label">Valor <span class="required">*</span></label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-circle"></i>
														</span>
														<input type="text" name="valor" class="form-control" placeholder="Ej.: 30" maxlength="3" required 
														onkeypress="return isNumberKey(event)"/>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-4 control-label">
													Prioridad <span class="required">*</span></label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-list-ol"></i>
														</span>
														<input type="text" name="prioridad" 
														class="form-control" placeholder="Ej.: 12"
														onkeypress="return isNumberKey(event)" 
														maxlength="2" required/>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-4 control-label">Definici??n </label>
												<div class="col-sm-8">
													<textarea class="form-control" rows="3" id="textareaAutosize" name="definicion" data-plugin-textarea-autosize="" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 74px; width: 100%;"></textarea>
												</div>
											</div>
											<input id="url_img" type="hidden" name="imagen">
										
										</form>
										<hr class="solid short">
										<div class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-4 text-right">Imagen</label>
												<div class="frm_imgupl">
													<div class="col-sm-8">
														<form action="database/data-atributos.php" class="dropzone dz-square" 
															id="myAwesomeDropzone">
															<div class="dz-message" align="center">
																Haga clic o arrastre la imagen aqu??
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
								<footer class="panel-footer">
									<div class="row">
										<div class="col-sm-12" align="right">
											<button id="btn_nvo_atributo" class="btn btn-primary">Guardar</button>
											<div id="response"></div>
										</div>
									</div>
								</footer>
							
						</section>
					</div>
					<div class="col-sm-7">
						<section class="panel">
							<header class="panel-heading">
								<h2 class="panel-title">
									Atributos registrados
								</h2>
							</header>
							<div class="panel-body">
								<table class="table table-bordered table-striped mb-none 
								listado_atributos_gral" id="datatable-default">
									<thead>
										<tr>
											<th></th>
											<th>Nombre</th>
											<th>Valor</th>
											<th>Prioridad</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $atributos as $a ) { ?>
										<tr class="gradeX">
											<td>
												<img src="<?php echo $a["imagen"]; ?>" width="30">
											</td>
											<td><a href="#!" data-toggle="popover" data-container="body" data-placement="top" title="Definici??n" data-content="<?php echo $a["definicion"]; ?>"><?php echo $a["nombre"]; ?></a></td>
											<td><?php echo $a["valor"]; ?></td>
											<td><?php echo $a["prioridad"]; ?></td>
											<td class="actions">
												<a href="editar_atributo.php?id=<?php echo $a['idATRIBUTO'] ?>" 
													class="on-default edit-row">
													<i class="fa fa-pencil"></i>
												</a>
												<?php 

													if( esBorrable( $dbh, $a["idATRIBUTO"] ) ) { ?>
														<a href="#modalAnim" class="mb-xs mt-xs mr-xs eatributo modal-with-move-anim" 
														data-ida="<?php echo $a['idATRIBUTO']; ?>" 
														style="margin-left: 10px;" id="ea<?php echo $a['idATRIBUTO']; ?>">
															<i class="fa fa-trash-o"></i>
													</a>
												<?php } ?>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</section>
					</div>
					<!-- end: page -->
				</section>
			</div>

		</section>

		<?php include( "sections/modals/confirmar-accion.html" ); ?>
		<input id="idatributo" type="hidden">

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="assets/vendor/select2/select2.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		<script src="assets/vendor/pnotify/pnotify.custom.js"></script>
		<script src="assets/vendor/dropzone/dropzone.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>
		<script src="js/init.modals.js"></script>

		<!-- Custom scripts -->
		<script src="js/init-tables-default.js"></script>
		<script src="js/fn-ui.js"></script>
		<script src="js/fn-atributos.js"></script>
		<script type="text/javascript">
			$( document ).ready(function() {
				Dropzone.options.myAwesomeDropzone = {
				  maxFiles: 1,
				  accept: function(file, done) {
				    console.log(file);
				    done();
				  },
				  init: function() {
				    this.on("maxfilesexceeded", function( file ){
				        notificar( "Producto", "Solo una imagen es permitida", "error" );
				    });
				    this.on("success", function(){
				        var args = Array.prototype.slice.call(arguments);
						$("#url_img").val( args[1] );
				    });
				  }
				};
			});

		</script>
		
	</body>
</html>