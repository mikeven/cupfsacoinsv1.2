<?php
	/* --------------------------------------------------------- */
	/* Cupfsa Coins - Datos sobre usuarios */
	/* --------------------------------------------------------- */
	/* --------------------------------------------------------- */
	function obtenerUsuarioPorId( $dbh, $id_u ){
		// Devuelve el registro de un usuario dado su id
		$q = "select idUSUARIO, nombre, apellido, email, cargo, idDepartamento, token_ingreso, 
		activo, date_format(fecha_creacion,'%d/%m/%Y') as fregistro 
		from usuario where idUSUARIO = $id_u";

		return mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
	}
	/* --------------------------------------------------------- */
	function obtenerUsuarioPorToken( $dbh, $token ){
		//Devuelve los datos de un usuario dado su token
		$q = "select * from usuario where token_ingreso = '$token'";
		$data_user = mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
		return $data_user;					
	}
	/* --------------------------------------------------------- */
	function obtenerDataUsuarioPorId( $dbh, $id_u ){
		// Devuelve el registro amplio de un usuario dado su id
		$q = "select u.idUSUARIO, u.nombre, u.apellido, u.email, u.token_ingreso, d.nombre as departamento,  
		date_format(u.fecha_creacion,'%d/%m/%Y') as fregistro from usuario u, departamento d 
		where u.idDepartamento = d.idDepartamento and idUSUARIO = $id_u";

		return mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
	}
	/* --------------------------------------------------------- */
	function obtenerIdDepartamentoUsuario( $dbh, $id_u ){
		// Devuelve el id de departamento de un usuario dado su id
		$q = "select idDepartamento from usuario where idUSUARIO = $id_u";
		$data = mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
		
		return $data["idDepartamento"];
	}
	/* --------------------------------------------------------- */
	function obtenerUsuariosRegistrados( $dbh ){
		// Devuelve todos los registros de usuarios
		$q = "select u.idUSUARIO, u.nombre, u.apellido, u.email, u.cargo, u.idDepartamento as iddpto, 
		d.nombre as departamento, u.activo, date_format(u.fecha_creacion,'%d/%m/%Y') as fregistro, u.token_ingreso 
		from usuario u, departamento d where u.idDepartamento = d.idDepartamento order by nombre asc";
		
		$data = mysqli_query( $dbh, $q );

		return obtenerListaRegistros( $data );
	}
	/* --------------------------------------------------------- */
	function obtenerRolesRegistrados( $dbh ){
		// Devuelve todos los registros de usuarios
		$q = "select idROL, nombre, descripcion from rol";
		
		$data = mysqli_query( $dbh, $q );

		return obtenerListaRegistros( $data );
	}
	/* --------------------------------------------------------- */
	function obtenerDepartamentos( $dbh ){
		// Devuelve todos los departamentos registrados
		$q = "select idDepartamento, nombre from departamento";
		
		$data = mysqli_query( $dbh, $q );

		return obtenerListaRegistros( $data );
	}
	/* --------------------------------------------------------- */
	function obtenerAdministrador( $dbh ){
		// Obtiene el VP del departamento indicado por id
		$q = "select idUSUARIO, nombre, email, token_ingreso from usuario where idUSUARIO in 
		(select idUSUARIO from usuario_rol where idROL = 1)";
		
		return mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
	}
	/* --------------------------------------------------------- */
	function obtenerVPDepartamento( $dbh, $iddpto ){
		// Obtiene el VP del departamento indicado por id
		$q = "select idUSUARIO, nombre, email, token_ingreso from usuario where idDepartamento = $iddpto and 
		idUSUARIO in (select idUSUARIO from usuario_rol where idROL = 4)";
		
		return mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
	}
	/* --------------------------------------------------------- */
	function obtenerCantidadUsuariosRol( $dbh, $idROL ){
		// Devuelve la cantidad de usuarios que poseen un rol, dado su id
		$q = "select count(*) as nro from usuario_rol where idROL = $idROL";
		$data = mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
		
		return $data["nro"]; 
	}
	/* --------------------------------------------------------- */
	function rolesUsuario( $dbh, $idu ){
		// Devuelve los roles que posee un usuario
		$q = "select r.idROL, r.nombre from rol r, usuario_rol ur 
		where ur.idROL = r.idROL and ur.idUSUARIO = $idu";
		
		$data = mysqli_query( $dbh, $q );
		$lista = obtenerListaRegistros( $data );
		return $lista;
	}
	/* --------------------------------------------------------- */
	function esRol( $dbh, $idr, $idu ){
		// Devuelve si un usuario posee el rol especificado por parámetro
		$esrol = false;
		$roles = rolesUsuario( $dbh, $idu );
		foreach ( $roles as $rol ) {
			if( $rol["idROL"] == $idr ) { $esrol = true; break; }
		}

		return $esrol;
	}
	/* --------------------------------------------------------- */
	function agregarUsuario( $dbh, $usuario ){
		// Guarda el registro de un usuario nuevo
		$q = "insert into usuario ( nombre, apellido, email, token_ingreso, password, cargo, activo, idDepartamento,  
		fecha_creacion ) values ( '$usuario[nombre]', '$usuario[apellido]', '$usuario[email]', '$usuario[token_a]', 
		'$usuario[password]', '$usuario[cargo]', 1, $usuario[departamento], NOW() )";
		
		$data = mysqli_query( $dbh, $q );
		return mysqli_insert_id( $dbh );
	}
	/* --------------------------------------------------------- */
	function editarUsuario( $dbh, $usuario ){
		// Actualiza los datos de un usuario
		$q = "update usuario set nombre = '$usuario[nombre]', apellido = '$usuario[apellido]', 
		email = '$usuario[email]', idDepartamento = $usuario[departamento], cargo = '$usuario[cargo]', 
		fecha_modificado = NOW() where idUSUARIO = $usuario[idusuario]";
		//echo $q;
		
		$data = mysqli_query( $dbh, $q );
		return mysqli_affected_rows( $dbh );
	}
	/* --------------------------------------------------------- */
	function actualizarPassWordUsuario( $dbh, $usuario ){
		// Actualiza contraseña de un usuario
		$q = "update usuario set password = '$usuario[password]', fecha_modificado = NOW() 
				where idUSUARIO = $usuario[idusuario]";
		
		$data = mysqli_query( $dbh, $q );
		return mysqli_affected_rows( $dbh );
	}
	/* --------------------------------------------------------- */
	function agregarAsociacionRolUsuario( $dbh, $idu, $idr ){
		// Registra la asociación de un usuario con su rol
		$q = "insert into usuario_rol ( idUSUARIO, idROL ) values ( $idu, $idr )";
		
		$data = mysqli_query( $dbh, $q );
	}
	/* --------------------------------------------------------- */
	function quorumVotacion( $dbh, $votos ){
		// Devuelve verdadero si existe la mayoría necesaria de votos 
		// para evaluar una nominación. Rol evaluador: 3
		$quorum = false;
		$votantes = obtenerCantidadUsuariosRol( $dbh, 3 );
		
		$mayoria = ceil( $votantes / 2 ) + 1;
		
		if( $votos >= $mayoria )
			$quorum = true;

		return $quorum;
	}
	/* --------------------------------------------------------- */
	function obtenerSumaAdjudicada( $dbh, $idu ){
		// Devuelve la suma acumulada de nominaciones adjudicadas por un usuario
		$q = "select sum( valor_atributo ) as coins from nominacion 
		where estado = 'adjudicada' and idNOMINADO = $idu";

		return mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
	}
	/* --------------------------------------------------------- */
	function obtenerSumaCanjes( $dbh, $idu ){
		// Devuelve la suma acumulada de canjes realizados por un usuario
		$q = "select sum( valor ) as coins from canje where idUSUARIO = $idu";

		return mysqli_fetch_array( mysqli_query ( $dbh, $q ) );
	}
	/* --------------------------------------------------------- */
	function obtenerCoinsUsuario( $dbh ,$idu ){
		// Devuelve la cantidad de coins disponibles de un usuario
		$coins_adjudicados 	= obtenerSumaAdjudicada( $dbh, $idu );
		$coins_canjeados 	= obtenerSumaCanjes( $dbh, $idu );

		return $coins_adjudicados["coins"] - $coins_canjeados["coins"];
	}
	/* --------------------------------------------------------- */
	function asociarRolesUsuario( $dbh, $usuario ){
		// Asigna cada rol asociado a un usuario para su registro
		foreach ( $usuario["rol"] as $rol ) {
			agregarAsociacionRolUsuario( $dbh, $usuario["idusuario"], $rol );
		}
	}
	/* --------------------------------------------------------- */
	function desvincularRolesUsuario( $dbh, $usuario ){
		// Elimina todos los roles que posee un registro de usuario

		$q = "delete from usuario_rol where idUSUARIO = $usuario[idusuario]";
		$data = mysqli_query( $dbh, $q );
		return mysqli_affected_rows( $dbh ); 
	}
	/* --------------------------------------------------------- */
	function registrosAsociadosUsuario( $dbh, $idu ){
		// Determina si existe un registro de tabla asociada a un usuario
		// Tablas relacionadas: nominacion, voto

		$rel_1 = registroAsociadoTabla( $dbh, "nominacion", "idNOMINADOR", $idu );
		$rel_2 = registroAsociadoTabla( $dbh, "nominacion", "idNOMINADO", $idu );
		$rel_3 = registroAsociadoTabla( $dbh, "nominacion", "idADMIN", $idu );
		$rel_4 = registroAsociadoTabla( $dbh, "voto", "idUSUARIO", $idu );

		return ( $rel_1 || $rel_2 || $rel_3 || $rel_4 );
	}
	/* --------------------------------------------------------- */
	function eliminarVinculosUsuario( $dbh, $idu ){
		// Elimina los registros de un usuario en los canjes y roles de usuario

		$q1 = "delete from canje where idUSUARIO = $idu";
		$q2 = "delete from usuario_rol where idUSUARIO = $idu";

		mysqli_query( $dbh, $q1 ); 	mysqli_query( $dbh, $q2 );
	}
	/* --------------------------------------------------------- */
	function eliminarUsuario( $dbh, $idu ){
		// Elimina un registro de usuario
		$q = "delete from usuario where idUSUARIO = $idu";
		
		return mysqli_query( $dbh, $q );
	}
	/* --------------------------------------------------------- */
	function generarTokenAcceso( $usuario ){
		// Devuelve el token de acceso para un usuario
		$time_stamp = date_timestamp_get( date_create() ) ;
		return md5( $usuario["email"].$time_stamp );
	}
	/* --------------------------------------------------------- */
	function obtenerMensajeEventoUsuario( $dbh, $idm ){
		// Devuelve el mensaje base para enviar por email de acuerdo a un evento

		$q = "select asunto, texto from mailing where id = $idm";
		
		return mysqli_fetch_array( mysqli_query( $dbh, $q ) );
	}
	/* --------------------------------------------------------- */
	function mensajeMailUsuario( $dbh, $usuario, $idmje ){
		// Prepara los datos para enviar un mensaje por email
		$mensaje = obtenerMensajeEventoUsuario( $dbh, $idmje );
		enviarMensajeEmail( $idmje, $mensaje, $usuario );
	}
	/* --------------------------------------------------------- */
	if( isset( $_POST["form_nu"] ) ){
		// Solicitud para registrar nuevo usuario

		include( "bd.php" );	
		include( "../fn/fn-mailing.php" );
		
		parse_str( $_POST["form_nu"], $usuario );
		$usuario = escaparCampos( $dbh, $usuario );
		if( isset( $usuario["rol"] ) ){

			$usuario["token_a"] = generarTokenAcceso( $usuario );
			$id = agregarUsuario( $dbh, $usuario );
			$usuario["idusuario"] = $id;
			
			if( ( $id != 0 ) && ( $id != "" ) ){

				mensajeMailUsuario( $dbh, $usuario, 22 );
				asociarRolesUsuario( $dbh, $usuario );
				$res["exito"] = 1;
				$res["mje"] = "Registro de usuario exitoso";
				$res["reg"] = $usuario;
			} else {
				$res["exito"] = 0;
				$res["mje"] = "Error al registrar usuario";
				$res["reg"] = NULL;
			}
		}else{
			$res["exito"] = 0;
			$res["mje"] = "No se especificaron roles de usuario";
		}

		echo json_encode( $res );
	}
	/* --------------------------------------------------------- */
	if( isset( $_POST["form_mu"] ) ){
		// Solicitud para modificar usuario

		include( "bd.php" );	
		
		parse_str( $_POST["form_mu"], $usuario );
		
		$usuario = escaparCampos( $dbh, $usuario );
		if( isset( $usuario["rol"] ) ){
			$rsp = editarUsuario( $dbh, $usuario );
			if( ( $rsp != 0 ) && ( $rsp != "" ) ){
				desvincularRolesUsuario( $dbh, $usuario );
				asociarRolesUsuario( $dbh, $usuario );
				$res["exito"] = 1;
				$res["mje"] = "Datos de usuario actualizados";
				$res["reg"] = $usuario;
			} else {
				$res["exito"] = 0;
				$res["mje"] = "Error al modificar usuario";
				$res["reg"] = NULL;
			}
		}else{
			$res["exito"] = 0;
			$res["mje"] = "No se especificaron roles de usuario";
		}

		echo json_encode( $res );
	}
	/* --------------------------------------------------------- */
	if( isset( $_POST["form_actpass"] ) ){
		// Solicitud para actualizar contraseña

		include( "bd.php" );	
		
		parse_str( $_POST["form_actpass"], $usuario );
		
		$usuario = escaparCampos( $dbh, $usuario );
		$rsp = actualizarPassWordUsuario( $dbh, $usuario );
		
		if( ( $rsp != 0 ) && ( $rsp != "" ) ){
			$res["exito"] = 1;
			$res["mje"] = "Contraseña actualizada";
			$res["reg"] = $usuario;
		} else {
			$res["exito"] = 0;
			$res["mje"] = "Error al actualizar contraseña";
			$res["reg"] = NULL;
		}

		echo json_encode( $res );
	}
	/* --------------------------------------------------------- */
	if( isset( $_POST["elim_usuario"] ) ){
		// Solicitud para eliminar usuario

		include( "bd.php" );
		
		eliminarVinculosUsuario( $dbh, $_POST["elim_usuario"] );
		$rsp = eliminarUsuario( $dbh, $_POST["elim_usuario"] );
		
		if( ( $rsp != 0 ) && ( $rsp != "" ) ){
			$res["exito"] = 1;
			$res["mje"] = "Usuario eliminado con éxito";
		} else {
			$res["exito"] = 0;
			$res["mje"] = "Error al eliminar usuario";
			$res["reg"] = NULL;
		}
		
		echo json_encode( $res );
	}
	/* --------------------------------------------------------- */
	if( isset( $_POST["email"] ) ){
		
		include ( "bd.php" );
		$id_u = "";
		if( isset( $_POST["id_u"] ) ) $id_u = $_POST["id_u"];

		$regs = obtenerUsuariosRegistrados( $dbh );
		foreach ( $regs as $r ) {
			if( $r["idUSUARIO"] != $id_u ){
				$emails[] = $r["email"]; 
			}
		}
		
		if( !in_array( $_POST["email"], $emails ) ) 
			$respuesta = true;
		else $respuesta = "Dirección de email ya registrada";

		echo json_encode( $respuesta );
	}
	/* --------------------------------------------------------- */
	//Obtener datos de usuario a partir de token
	if( isset( $_GET["token"] ) ){

		$usuario = obtenerUsuarioPorToken( $dbh, $_GET["token"] );

	}
	/* --------------------------------------------------------- */
?>