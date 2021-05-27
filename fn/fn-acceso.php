<?php 
	/* --------------------------------------------------------- */
	/* Cupfsa Coins - Funciones sobre sesión de usuario y accesos */
	/* --------------------------------------------------------- */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	function hrolesUsuario( $data_roles ){
		//
		$roles = "";
		
		foreach ( $data_roles as $r ) {
			$roles .= $r["nombre"]." ";
		}

		return $roles;
	}
	/* --------------------------------------------------------- */
	function obtenerEsquemaAccesosUsuario(){
	
		$usuario = $_SESSION["user"];

		return $usuario;
	}
	/* --------------------------------------------------------- */
	function obtenerListaAccesoSecciones( $data_accesos ){
		//Devuelve la lista de secciones accesibles por los permisos del usuario
		$lista = array();
		foreach ( $data_accesos as $a ) {
			$lista[] = $a["codigo"];
		}
		return $lista;
	}
	/* --------------------------------------------------------- */
	function isV( $seccion ){
		//Determina si una sección del sistema es visible para el usuario
		//if( isSU() ) return true;
		
		$visible = false;
		$permisos = $_SESSION["user"]["accesos"];
		if ( in_array( $seccion, $permisos ) ){
			$visible = true;
		}
		return $visible;
	}
	/* --------------------------------------------------------- */
	function isAccesible( $pagina ){
		//Determina si una página del sistema es accesible para el usuario
		if( !isV( $pagina ) ) header('Location: inicio.php');
	}
	/* --------------------------------------------------------- */
	function mostrarResultadosNominacion( $es_vp, $es_usadm, $votada ){
		//Determina si es visible los resultados de votación de una nominación
		$mostrar = false;

		if( isV( "result_nom" ) && !$es_vp ){		// Si puede ver resultados, no es VP
			if( isV( "en_votar" ) ){				// Si puede votar
				if( $votada )						// Si ya votó por la nominación
					$mostrar = true;
				else 
					if( $es_usadm ) 
						$mostrar = true;
			}else
				$mostrar = true;

		}
			
		return $mostrar;
	}
	/* --------------------------------------------------------- */
	function verificarPasswordPorDefectoUsuario( $dbh, $idu ){
		// Redirige al usuario a su perfil para actualizar su contraseña en caso que posea una contraseña por defecto
		$pwd_defecto 		= false;
		$data_usuario 		= usuarioConPasswordPorDefecto( $dbh, $idu );
		$pwd_defecto 		= $data_usuario["password_defecto"];
		
		if( $pwd_defecto )
			echo '<script type="text/javascript">', '$("#pwdalerta").click();' , '</script>';
			//header('Location: miperfil.php?password_defecto');
	}
	/* --------------------------------------------------------- */

	$accesos_usess = obtenerEsquemaAccesosUsuario();


?>