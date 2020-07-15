<?php


Class APIManager
{

	public function Init()
	{

		$uri = explode( "/", substr( $_SERVER['REQUEST_URI'], 1, strlen( $_SERVER['REQUEST_URI'])));

		# Comprobamos que con la ruta viene la versión de la API que utilizamos
		if ( ConfigClass::get("config.api_version") != $uri['0'])
		{

			return ( MessagesClass::Response( array(
	        'success' => false,
	        'type' => 'ERROR',
	        'code' => 'AUTH0001-' . CODE_ERROR,
	        'message' => ConfigClass::get("messages.AUTH0001"),
	      )
	    ));

		}

		debug( $uri);

		# Validamos la api con el access_token o api_key.
		# Si la ruta que viene es oauth entonces la API solicita un access_token con el api_key

		if ( $uri[1] == 'oauth')
		{



		}
	}

}