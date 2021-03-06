// Productos
/*
 * fn-productos.js
 *
 */
/* --------------------------------------------------------- */	
/* --------------------------------------------------------- */
(function() {
	
	'use strict';

	// basic
	$("#frm_nproducto").validate({
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			label.remove();
		},
		rules: {
		    valor: { digits: true },
		    nombre: {
		        remote: {
		        	url: "database/data-productos.php",
		        	method: 'POST'       	
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
		submitHandler: function (form) {
            // Formulario validado
            agregarProducto();
            return false; 
            // block the default form action if using ajax
        }
	});

	$("#frm_mproducto").validate({
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			label.remove();
		},
		rules: {
		    valor: { digits: true },
		    nombre: {
		        remote: {
		        	data: { idproducto_edit: $('#idproducto').val() },
		        	url: "database/data-productos.php",
		        	method: 'POST'       	
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
		submitHandler: function (form) {
            // Formulario validado
            editarProducto();
            return false; 
            // block the default form action if using ajax
        }
	});

	// validation summary
	var $summaryForm = $("#summary-form");
	$summaryForm.validate({
		errorContainer: $summaryForm.find( 'div.validation-message' ),
		errorLabelContainer: $summaryForm.find( 'div.validation-message ul' ),
		wrapper: "li"
	});

	// checkbox, radio and selects
	$("#chk-radios-form, #selects-form").each(function() {
		$(this).validate({
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-error');
			}
		});
	});

}).apply( this, [ jQuery ]);

/* --------------------------------------------------------- */
function agregarProducto(){
	// Invocaci??n as??ncrona para agregar nuevo producto
	var fs = $('#frm_nproducto').serialize();
	var espera = "<img src='assets/images/loading.gif' width='35'>";
	
	$.ajax({
        type:"POST",
        url:"database/data-productos.php",
        data:{ form_np: fs  },
        beforeSend: function() {
        	$("#response").html( espera );
        	$("#btn_nvo_prod").prop("disabled", "true");
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 ){
				$("#response").fadeOut();
				var idr = res.reg.id;
    			enviarRespuesta( res, "redireccion", "producto.php?id=" + idr );
			}
			else
				notificar( "Producto", res.mje, "error" );
        }
    });
}
/* --------------------------------------------------------- */
function editarProducto(){
	// Invocaci??n as??ncrona para modificar producto
	var fs = $('#frm_mproducto').serialize();
	var espera = "<img src='assets/images/loading.gif' width='35'>";
	
	$.ajax({
        type:"POST",
        url:"database/data-productos.php",
        data:{ form_mp: fs  },
        beforeSend: function() {
        	$("#response").html( espera );
        	$("#btn_mod_prod").prop( "disabled", "true" );
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 ){
				$("#response").fadeOut();
				var idr = res.reg.idproducto;
				notificar( "Producto", res.mje, "success" );
    			enviarRespuesta( res, "redireccion", "producto.php?id=" + idr );
			}
			else
				notificar( "Producto", res.mje, "error" );
        }
    });
}
/* --------------------------------------------------------- */
function eliminarProducto(){
	// Invocaci??n as??ncrona para eliminar producto
	var espera = "<img src='assets/images/loading.gif' width='35'>";
	var idp = $("#idproducto").val();
	var img_producto = $("#img_producto").attr("src");

	$.ajax({
        type:"POST",
        url:"database/data-productos.php",
        data:{ elim_prod:idp , img: img_producto },
        beforeSend: function() {
        	$("#response").html( espera );
        	$("#btn_mod_prod").prop( "disabled", "true" );
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 ){
				$("#response").fadeOut();
    			enviarRespuesta( res, "redireccion", "productos.php" );
			}
			else
				notificar( "Producto", res.mje, "error" );
        }
    });
}
/* --------------------------------------------------------- */
function iniciarBotonEliminarProducto(){
	//Asigna los textos de la ventana de confirmaci??n para desvincular una l??nea de un producto
    iniciarVentanaModal( "btn_elim_prod", "btn_canc", 
                         "Eliminar producto", 
                         "??Confirma que desea eliminar este producto", 
                         "Confirmar acci??n" );

    $("#btn_elim_prod").on('click', function (e) {
		// Invoca la eliminaci??n de un producto
		eliminarProducto();
	});
}
/* --------------------------------------------------------- */
function entregarCanje(){
	// Invoca al servidor para marcar canje como entregado
	var idcanje = $("#idacanje").val();
	
	$.ajax({
        type:"POST",
        url:"database/data-productos.php",
        data:{ canje_entregado: idcanje },
        beforeSend: function() { },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 ){
    			enviarRespuesta( res, "redireccion", "canjes.php" );
			}
			else
				notificar( "Canjes", res.mje, "error" );
        }
    });
}
/* --------------------------------------------------------- */
$("#btn_nvo_prod").on('click', function (e) {
	// Invoca el env??o del formulario de nuevo producto
	$("#frm_nproducto").submit();
	
});
/* --------------------------------------------------------- */
$("#btn_mod_prod").on('click', function (e) {
	// Invoca el env??o del formulario de edici??n de producto
	$("#frm_mproducto").submit();
});
/* --------------------------------------------------------- */
$(".listado_productos_gral").on( "click", ".eprod", function (e) {
	// Inicializa la ventana modal para confirmar la eliminaci??n de un producto
	 
	$("#idproducto").val( $(this).attr( "data-idp" ) );
	$("#img_producto").attr( "src", $(this).attr( "data-imgsrc" ) );
    iniciarBotonEliminarProducto();
});
/* --------------------------------------------------------- */
$("#btn_canje").on('click', function (e) {
	// Invocaci??n as??ncrona para canjear producto
	var fs = $('#frm_ncanje').serialize();
	var espera = "<img src='assets/images/loading.gif' width='35'>";

	$.ajax({
        type:"POST",
        url:"database/data-productos.php",
        data:{ form_ncje: fs  },
        beforeSend: function() {
        	$('#frm_ncanje').slideUp( 300 );
        	$('#frm_ncanje').html( espera );
        },
        success: function( response ){
        	console.log( response );
			res = jQuery.parseJSON( response );
			if( res.exito == 1 ){
				$('#frm_ncanje').html( "" );
    			enviarRespuesta( res, "redireccion", "mis-canjes.php" );
			}
			else
				notificar( "Producto", res.mje, "error" );
        }
    });
});
/* --------------------------------------------------------- */
$(".listado_canjes").on( "click", ".recanje", function (e) {
	//Inicializa la ventana modal para confirmar registrar la entrega de un canje
	
	$("#idacanje").val( $(this).attr( "data-idc" ) );
    
    iniciarVentanaModal( "btn_entregar_canje", "btn_canc", 
                         "Entrega de Canje", 
                         "??Confirma que desea marcar este canje como entregado?", 
                         "Confirmar acci??n" );

    $("#btn_entregar_canje").on('click', function (e) {
		// Invoca la llamada a marcar canje como entregado
		entregarCanje();
	});
});
/* --------------------------------------------------------- */