<?php 
	/* --------------------------------------------------------- */
	/* Cupfsa Coins - Funciones auxiliares sobre nominaciones */
	/* --------------------------------------------------------- */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	function esVotable( $dbh, $idu, $nominacion ){
		// Devuelve verdadero/falso sobre si el usuario puede votar una nominación
		$vota = false;
		if( isV( 'en_votar' ) && !esVotada( $dbh, $idu, $nominacion["idNOMINACION"] ) 
			&& ( $nominacion["estado"] == "pendiente" 		|| 
				 $nominacion["estado"] == "pendiente_ss" 	|| 
				 $nominacion["estado"] == "sustento"  		|| 
				 $nominacion["estado"] == "validada" ) 
			&& !soyNominador( $idu, $nominacion ) && !soyNominado( $idu, $nominacion ) ){
			$vota = true;
		}

		return $vota;
	}
	/* --------------------------------------------------------- */
	function yaVotada( $dbh, $idu, $nominacion ){
		// Devuelve verdadero si un puede votar una nominación y ya tiene voto registrado
		$votada = false;
		if( isV( 'en_votar' ) && esVotada( $dbh, $idu, $nominacion["idNOMINACION"] ) 
			&& ( $nominacion["estado"] == "pendiente" 		|| 
				 $nominacion["estado"] == "pendiente_ss" 	|| 
				 $nominacion["estado"] == "sustento"  		|| 
				 $nominacion["estado"] == "validada" ) ){
			$votada = true;
		}
		return $votada;
	}
	/* --------------------------------------------------------- */
	function enlaceVerNominacion( $dbh, $idu, $nom ){
		// Devuelve el enlace correspondiente a una nominación segun rol/estado 
		// de votación
		if( isV( "en_votar" ) ){ 	// Acceso a votación
			if( esVotable( $dbh, $idu, $nom ) )
				$enl = '<i class="fa fa-hand-o-down"></i> Votar</a>';
			else
				$enl = '<i class="fa fa-eye"></i> Ver</a>';
		}
		if( isV( "en_ver_nom" ) ) 	// Acceso a ver nominaciones
			$enl = '<i class="fa fa-eye"></i> Ver</a>';
		
		return $enl;
	}
	/* --------------------------------------------------------- */
	function estadoNominacion( $estado ){
		// Devuelve la etiqueta de estado de nominación según valor
		$etiquetas = array(
			"pendiente" 		=> "Pendiente",				// Nominación recién creada
			"pendiente_ss" 		=> "Pendiente",				// Pendiente: Admin debe aprobar sustento
			"pendiente_svp" 	=> "Pendiente",				// Pendiente: VP debe aprobar sustento 
			"sustento"			=> "Espera por sustento",	// Pendiente por sustento pedido por Admin
			"sustento_vp"		=> "Espera por sustento VP",// Pendiente por sustento pedido por VP
			"aprobada"			=> "Aprobada",				// Aprobada por admin o VP
			"validada"			=> "Validada",				// Validada por VP
			"rechazada"			=> "Rechazada",				// Rechazada por Admin o VP
			"adjudicada"		=> "Adjudicada"				// Adjudicada al nominado
		);

		return $etiquetas[$estado];
	}	
	/* --------------------------------------------------------- */
	function iconoEstadoNominacion( $estado ){
		// Devuelve el ícono de estado de nominación según valor
		$iconos = array(
			"pendiente" 		=> "<i class='fa fa-clock-o'></i>",
			"pendiente_ss" 		=> "<i class='fa fa-clock-o'></i>",
			"pendiente_svp" 	=> "<i class='fa fa-clock-o'></i>",
			"sustento"			=> "<i class='fa fa-file-o'></i>",
			"sustento_vp"		=> "<i class='fa fa-file-o'></i>",
			"validada"			=> "<i class='fa fa-check-circle'></i>",
			"aprobada"			=> "<i class='fa fa-check-square-o'></i>",
			"rechazada"			=> "<i class='fa fa-times'></i>",
			"adjudicada"		=> "<i class='fa fa-gift'></i>"
		);

		return $iconos[$estado];
	}
	/* --------------------------------------------------------- */
	function claseEstadoNominacion( $estado ){
		// Devuelve la clase para asignar fondo de nominaciones según estado
		$iconos = array(
			"pendiente" 		=> "bg-dark",
			"pendiente_ss" 		=> "bg-dark",
			"pendiente_svp" 	=> "bg-dark",
			"sustento"			=> "bg-warning",
			"sustento_vp"		=> "bg-warning",
			"aprobada"			=> "bg-success",
			"validada" 			=> "bg-tertiary",
			"rechazada"			=> "bg-secondary",
			"adjudicada"		=> "bg-quartenary"
		);

		return $iconos[$estado];
	}
	/* --------------------------------------------------------- */
	function nominacionVisible( $dbh, $idu, $nominacion ){
		// Devuelve verdadero si el contenido de una nominación es visible según perfil y estado
		$visible = false;
		$es_vp 	= esRol( $dbh, 4, $idu );
		$es_adm = esRol( $dbh, 1, $idu );

		if( $nominacion == NULL ) return false;

		// Si es perfil colaborador siendo nominador o nominado con nominación aprobada
		if( isV( 'pan_nom_apoyo' ) && (( $nominacion["idNOMINADOR"] == $idu ) || 
										( $nominacion["idNOMINADO"] == $idu && 
										( $nominacion["estado"] == "aprobada" || $nominacion["estado"] == "adjudicada") ) ) )
			$visible = true;

		// Perfiles administrador o evaluador
		if( $es_adm || isV( 'en_votar' ) ) 
			$visible = true;

		// Perfil VP
		if( esVisiblePorVP( $dbh, $idu, $nominacion ) )
			$visible = true;

		return $visible;
	}
	/* --------------------------------------------------------- */
	function obtenerNombreTitulo( $p ){
		// Devuelve el texto complementario para mostrar en la página de nominaciones
		$titulo = "";
		if( $p == "recibidas" )
			$titulo = $p;
		if( $p == "hechas" ) $titulo = "realizadas";

		return $titulo;
	}
	/* --------------------------------------------------------- */
	function obtenerListadoNominaciones( $dbh, $idu ){
		// Devuelve los registros de nominaciones de acuerdo al perfil para mostrar en la página de nominaciones

		if( isV( 'mp_nom_pers' ) ){ 		// Acceso a nominaciones hechas/recibidas
			if( isset( $_GET["param"] ) ){
				$data["titulo"] = obtenerNombreTitulo( $_GET["param"] );
				$data["nominaciones"] = obtenerNominacionesAccion( $dbh, $idu, $_GET["param"] );
			}else{
				$data["titulo"] = obtenerNombreTitulo( "hechas" );
				$data["nominaciones"] = obtenerNominacionesAccion( $dbh, $idu, "hechas" );
			}
		}
		if( isV( 'ver_tnominac' ) && !isset( $_GET["param"] ) ){		
		// Acceso a ver todas las nominaciones sin parámetros: realizadas, recibidas
			$data["titulo"] = obtenerNombreTitulo( "" );
			$data["nominaciones"] = obtenerNominacionesRegistradas( $dbh );
		}

		return $data;
	}
	/* --------------------------------------------------------- */
	function esActivable( $nominacion ){
		// Devuelve verdadero si una nominación puede ser activada/desactivada para votación
		$activable = false;
		if( isV( 'en_activ_nom' ) && ( $nominacion["estado"] == "pendiente" || 
									   $nominacion["estado"] == "sustento" ) )
			$activable = true;

		return $activable;
	}
	/* --------------------------------------------------------- */
	function posicionSuiche( $votable ){
		// Devuelve checked si una nominación está abierta a votación
		$checked["p"] = ""; $checked["t"] = "Activar para votación";
		if( $votable ) {
			$checked["p"] = "checked"; 
			$checked["t"] = "Desactivar para votación";
		}
		
		return $checked;
	}
	/* --------------------------------------------------------- */
	function esRecibida(){
		// Determina si el listado de nominaciones son recibidas
		$recibidas = false;

		if( isset( $_GET["param"] ) &&  $_GET["param"] == 'recibidas' )
			$recibidas = true;

		return $recibidas;
	}

	function mostrarNominador( $nominacion ){
		// Muestra la etiqueta del nominador en listado de nominaciones recibidas
		echo "Nominado por: ".$nominacion["nombre1"]." ". $nominacion["apellido1"];
	}
	/* --------------------------------------------------------- */
	function enviaSustento( $idu, $nominacion ){
		// Determina si el usuario actual puede enviar sustentos
		$envia = false;

		if ( ( $nominacion["estado"] == "sustento" || $nominacion["estado"] == "sustento_vp" ) 
				&& $nominacion["idNOMINADOR"] == $idu )
			$envia = true;

		return $envia;
	}
	/* --------------------------------------------------------- */
	function enlNominacion( $nominacion, $recibida ){
		// Devuelve el enlace a la ficha de nominación en función si es recibida o no
		$param = ( $recibida ) ? "&recibida" : "";
		$lnk = "nominacion.php?id=$nominacion[idNOMINACION]".$param;

		return $lnk;
	}
	/* --------------------------------------------------------- */
	function solicitableSustento( $dbh, $idu, $nominacion ){
		// Evalúa si puede mostrarse la opción para solicitar sustento a una nominación por parte de un admin
		$solicitar_sustento = false;
		
		$es_admin = esRol( $dbh, 1, $idu );					//Rol 1: Administrador ( Admin )
		if( $nominacion["idNOMINADOR"] != $idu ){			// Nominador no es quien solicita sustento

			if( $nominacion["motivo2"] == "" && $nominacion["sustento2"] == "" && $es_admin 
				&& $nominacion["estado"] == "pendiente" ){
				$solicitar_sustento = true;
			}
		}

		return $solicitar_sustento;
	}
	/* --------------------------------------------------------- */
	function esVisibleEnLista( $dbh, $idu, $nominacion ){
		// Evalúa si una nominación es visible en el listado de nominaciones
		$visible = true;
		$vista_mis_nominaciones = false;

		/*if( esRol( $dbh, 4, $idu ) ){						//Rol 4: Vicepresidente ( VP ))
			$visible = esVisiblePorVP( $dbh, $idu, $nominacion );
		}*/
		if( isset( $_GET["param"] ) ){
			if( $_GET["param"] == "recibidas" || $_GET["param"] == "hechas" )
				$vista_mis_nominaciones = true;
		}

		/*if( esRol( $dbh, 3, $idu ) 
			&& ( soyNominador( $idu, $nominacion ) || soyNominado( $idu, $nominacion ) ) 
			&& !$vista_mis_nominaciones && !esRol( $dbh, 1, $idu ) )
			$visible = false;*/

		return $visible;
	}
	/* --------------------------------------------------------- */
	function solicitableSustentoVP( $nominacion ){
		// Evalúa si puede mostrarse la opción para solicitar sustento a una nominación para un VP
		$solicitar_sustento = false;

		if( $nominacion["sustento_vp"] == "" && $nominacion["motivo_vp"] == "" 
			&& $nominacion["estado"] != "sustento_vp" )
			$solicitar_sustento = true;

		return $solicitar_sustento;
	}
	/* --------------------------------------------------------- */
	function soyNominado( $idu, $nominacion ){
		// Evalúa si el usuario actual es el nominado de la nominación
		return ( $nominacion["idNOMINADO"] == $idu );
	}
	/* --------------------------------------------------------- */
	function soyNominador( $idu, $nominacion ){
		// Evalúa si el usuario actual es el nominador de la nominación
		return ( $nominacion["idNOMINADOR"] == $idu );
	}
	/* --------------------------------------------------------- */
	function esNominacionMismoDepartamento( $nominacion ){
		// Evalúa si una nominación está hecha entre usuarios del mismo departamento
		return ( $nominacion["iddpto_nominador"] == $nominacion["iddpto_nominado"] );
	}
	/* --------------------------------------------------------- */
	function esAprobadaPorVP( $dbh, $idu, $nominacion ){
		// Evalúa si una nominación es aprobada directamente por el VP del departamento del nominado y nominador. Caso: el VP no es el nominador.
		$aprobable 	= false;
		$mismo_dpto = false;

		$es_vp = esRol( $dbh, 4, $idu );	//Rol 4: Vicepresidente ( VP )
		
		$id_dpto_usuario = obtenerIdDepartamentoUsuario( $dbh, $idu );
		if ( $id_dpto_usuario == $nominacion["iddpto_nominador"] 
			&& $id_dpto_usuario == $nominacion["iddpto_nominado"] 
			&& $idu != $nominacion["idNOMINADO"] ) 
			$mismo_dpto = true;
		
		if( $mismo_dpto && $es_vp ) 
			$aprobable = true;

		return $aprobable;
	}
	/* --------------------------------------------------------- */
	function esValidadaPorVP( $dbh, $idu, $nominacion ){
		// Evalúa si una nominación es validada inicialmente por el VP del departamento del nominado.
		// Caso: nominador y nominado son de departamentos diferentes.
		$validable = false;
		$estados_validos = array( "pendiente", "sustento_vp", "pendiente_svp" );

		$es_vp = esRol( $dbh, 4, $idu );	//Rol 4: Vicepresidente ( VP )
		$id_dpto_usuario = obtenerIdDepartamentoUsuario( $dbh, $idu );
		$mismo_dpto = ( $id_dpto_usuario == $nominacion["iddpto_nominado"] );
		$esnominado = ( $idu == $nominacion["idNOMINADO"] );
		
		if( $es_vp && $mismo_dpto && in_array( $nominacion["estado"], $estados_validos ) && !$esnominado ) 
			$validable = true;

		return $validable;
	}
	/* --------------------------------------------------------- */
	function esVisiblePorVP( $dbh, $idu, $nominacion ){
		// Evalúa si una nominación es visible para un VP en el listado de nominaciones
		$visible = false;

		$id_dpto_usuario = obtenerIdDepartamentoUsuario( $dbh, $idu );
		$mismo_dpto = ( $id_dpto_usuario == $nominacion["iddpto_nominado"] );
		$esnominado = ( $idu == $nominacion["idNOMINADO"] );
		$esnominador = ( $idu == $nominacion["idNOMINADOR"] );
		
		if( ( $mismo_dpto && !$esnominado ) || 
			( $esnominado && $nominacion["estado"] == "adjudicada") ||
			( $esnominador ) ) 
			$visible = true;
		
		return $visible;
	}
	/* --------------------------------------------------------- */
	function esVPNominado( $dbh, $idu, $nominacion ){
		// Evalúa si el nominado de una nominación es VP

		return esRol( $dbh, 4, $nominacion["idNOMINADO"] );
	}
	/* --------------------------------------------------------- */
?>