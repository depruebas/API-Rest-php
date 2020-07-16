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
	        'http_code' => 'HTTP/1.1 404  Not Found',
	        'message' => ConfigClass::get("messages.AUTH0001"),
	      )
	    ));

		}

		$auth = new Authorization();

		# Validamos la api con el access_token o api_key.
		# Si la ruta que viene es oauth entonces la API solicita un access_token con el api_key

		if ( $uri[1] == 'oauth')
		{

			# Pasamos el POST que son los datos que tenemos que validar
			# Y devolvemos el resultado al cliente que inicia la petición porque devuelve un false con un error
			# o un true con un access_code
			return( $auth->ValidateKeys( $_POST));

		}


		# Si la uri es cualquier otra cosa que no oauth entonces sobre entendemos que viene en los headrs
		# un access_code y lo vamos a verificar

		# Obtenemos los headers de la peticion
		$headers = getallheaders();

    # Eliminamoslos headers que no vamos a tratar
    $keys = ConfigClass::get("config.keys");
    foreach ( $headers as $key => $value)
    {

      if ( !in_array( $key, $keys))
      {
        unset ( $headers[$key]);
      }

    }

    # Validamos el access_token que nos enviarn
    $isvalid_access_token = $auth->ValidateAccessToken( $headers);

    if ( !$isvalid_access_token)
    {

    	return ( MessagesClass::Response( array(
	        'success' => false,
	        'type' => 'ERROR',
	        'code' => 'AUTH0006-' . CODE_ERROR,
	        'http_code' => 'HTTP/1.1 401 Unauthorized',
	        'message' => ConfigClass::get("messages.AUTH0006"),
	      )
	    ));

    }

    # Si la validacion del access_token es correcta pasamos a procesar los metodos de acceso a que que nos piden
    # Obtenemos el metodo que se utiliza para llamar a la API, solo soportamos GET, POST, PUT y DELETE
    $method = $_SERVER['REQUEST_METHOD'];

    if ( !in_array( $method, ConfigClass::get("config.methods")))
    {

      return ( MessagesClass::Response( array(
          'success' => false,
          'type' => 'ERROR',
          'code' => 'AUTH0002-' . CODE_ERROR,
          'http_code' => 'HTTP/1.1 405 Method Not Allowed',
          'message' => str_replace( "#", $method, ConfigClass::get("messages.AUTH0002")),
        )
      ));

    }

    switch ( $method)
    {

    	case 'GET':

    		echo "El metodo es GET";
    		break;

    	case 'POST':

    		echo "El metodo es POST";
    		break;

    	case 'PUT':

    		echo "El metodo es PUT";
    		break;

    	case 'DELETE':

    		echo "El metodo es DELETE";
    		break;

    }


	}

}