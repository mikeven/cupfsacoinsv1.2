// Usuarios
/*
 * fn-usuarios.js
 *
 */
/* --------------------------------------------------------- */	
/* --------------------------------------------------------- */
(function() {

	'use strict';
	/* -------------------------------- */
	$("#frm_nusuario").validate({
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			label.remove();
		},
		rules: {
		    email: {
		        remote: {
		        	url: "database/data-usuarios.php",
		        	method: 'POST'
				}
			},
			rol: {
				required: true
			}
		},
		onkeyup: false,
		errorPlacement: function( error, element ) {
			var placement = element.closest('.input-group');
			if (!placement.get(0)) {
				placement = element;
			}
			if (error.text() !== '') {
				placement.after(error);
			}
		},
		submitHandler: function(form) {
		    agregarUsuario();
		}
	});
	
	$("#frm_musuario").validate({
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			label.remove();
		},
		rules: {
		    email: {
		        remote: {
		        	url: "database/data-usuarios.php",
		        	method: 'POST',
		        	data: {
		        		id_u: function() {
			            	return $('#idua').val();
			         	}
			     	}
				}
			}
		},
		onkeyup: false,
		errorPlacement: function( error, element ) {
			var placement = element.closest('.input-group');
			if (!placement.get(0)) {
				placement = element;
			}
			if (error.text() !== '') {
				placement.after(error);
			}
		},
		submitHandler: function(form) {
		    editarUsuario();
		}
	});
	/* -------------------------------- */
	$.validator.addMethod("regex", function(value, element, regexpr) {          
     return regexpr.test(value);
   	}, "La contrase??a debe cumplir el formato requerido"); 

	$("#frm_mpassword").validate({
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			label.remove();
		},
		rules: {
		    password: {
	            required: true,
	            minlength: 8,
	            regex: /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&*]).*$/
	        },
	        password_confirmada: {
	            required: true,
	            minlength: 8,
	            equalTo: "#password"
	        }
		},
		onkeyup: false,
		errorPlacement: function( error, element ) {
			var placement = element.closest('.input-group');
			if (!placement.get(0)) {
				placement = element;
			}
			if (error.text() !== '') {
				placement.after(error);
			}
		},
		submitHandler: function(form) {
			actualizarPassword();
		}
	});

}).apply( this, [ jQuery ]);
/* --------------------------------------------------------- */
function agregarUsuario(){
	// Invocaci??n as??ncrona para agregar nuevo usuario
	var fs = $('#frm_nusuario').serialize();
	var bot_reset = $("#btn_res_fnu");
	var espera = "<img src='assets/images/loading.gif' width='35'>";	

	$.ajax({
        type:"POST",
        url:"database/data-usuarios.php",
        data:{ form_nu: fs },
        beforeSend: function() {
        	$("#response").html( espera );
        	$("#btn_nvo_usuario").hide( 200 );
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 ){
				$( bot_reset ).click();
				notificar( "Nuevo usuario", res.mje, "success" );
				setTimeout( 
					function() { 
						enviarRespuesta( res, "redireccion", "usuarios.php" )
					}, 
				3000 );
			}
			else{
				notificar( "Nuevo usuario", res.mje, "error" );
				$("#btn_nvo_usuario").fadeIn( 200 );
			}

			$("#response").html( "" );
			
        }
    });
}
/* --------------------------------------------------------- */
function editarUsuario(){
	// Invocaci??n as??ncrona para editar usuario
	var fs = $('#frm_musuario').serialize();
	var espera = "<img src='assets/images/loading.gif' width='35'>";	

	$.ajax({
        type:"POST",
        url:"database/data-usuarios.php",
        data:{ form_mu: fs },
        beforeSend: function() {
        	$("#response").html( espera );
        	$("#btn_mod_usuario").fadeOut( 200 );
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 ){
				notificar( "Modificar usuario", res.mje, "success" );
			}
			else
				notificar( "Modificar usuario", res.mje, "error" );

			$("#response").html( "" );
        }
    });
}
/* --------------------------------------------------------- */
function actualizarPassword(){
	// Invocaci??n as??ncrona para actualizar password de usuario
	var fs = $('#frm_mpassword').serialize();
	var espera = "<img src='assets/images/loading.gif' width='35'>";	

	$.ajax({
        type:"POST",
        url:"database/data-usuarios.php",
        data:{ form_actpass: fs },
        beforeSend: function() {
        	$("#response").html( espera );
        	$("#btn_mod_passw").fadeOut( 200 );
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 ){
				notificar( "Perfil de usuario", res.mje, "success" );
				setTimeout( function() { location.reload(); }, 3000 );
			}
			else
				notificar( "Perfil de usuario", res.mje, "error" );

			$("#response").html( "" );
        }
    });
}
/* --------------------------------------------------------- */
function eliminarUsuario(){
	// Invocaci??n as??ncrona para eliminar usuario 	
	var idu = $("#idusuario").val();
	var espera = "<img src='assets/images/loading.gif' width='35'>";

	$.ajax({
        type:"POST",
        url:"database/data-usuarios.php",
        data:{ elim_usuario: idu },
        beforeSend: function() {
        	$( "#eu" + idu ).html( espera );
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 )
    			enviarRespuesta( res, "redireccion", "usuarios.php" );
			else
				notificar( "Usuarios", res.mje, "error" );
        }
    });
}
/* --------------------------------------------------------- */
function enviarEnlaceIngreso( idu ){
	// Invocaci??n as??ncrona para enviar enlace de ingreso a usuario

	var espera = "<img src='assets/images/loading.gif' width='35'>";

	$.ajax({
        type:"POST",
        url:"database/data-usuarios.php",
        data:{ enl_ing: idu },
        beforeSend: function() {
        	$( "#bot_enl_ing" ).html( espera );
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 )
    			$( "#bot_enl_ing" ).html( res.mje );
			else
				notificar( "Usuarios", res.mje, "error" );
        }
    });
}
/* --------------------------------------------------------- */
$(".listado_usuarios_gral").on( "click", ".eusuario", function (e) {
	//Inicializa la ventana modal para confirmar la eliminaci??n de un usuario
	//alert( $(this).attr( "data-idu" ) );
	$("#idusuario").val( $(this).attr( "data-idu" ) );
    
    iniciarVentanaModal( "btn_elim_usuario", "btn_canc", 
                         "Eliminar usuario", 
                         "??Confirma que desea eliminar este usuario", 
                         "Confirmar acci??n" );

    $("#btn_elim_usuario").on('click', function (e) {
		// Invoca la eliminaci??n de un usuario
		eliminarUsuario();
	});
});
/* --------------------------------------------------------- */

/* --------------------------------------------------------- */
$("#btn_enviar_lnk").on( "click", function (e) {
	// Invoca la llamada al servidor para enviar enlace de ingreso a un usuario
	var idu = $(this).attr( "data-u" );
	enviarEnlaceIngreso( idu );
});
/* --------------------------------------------------------- */
$("#lnk_act_password").on( "click", function (e) {
	// Invoca la llamada al servidor para enviar enlace de ingreso a un usuario
	$("#frm_mpassword").fadeToggle();
});


/* --------------------------------------------------------- */