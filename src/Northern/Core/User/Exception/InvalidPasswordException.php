<?php

namespace Northern\Core\User\Exception;

class InvalidPasswordException extends UserException {

	public function __construct( \Exception $previous = NULL )
	{
		parent::__construct("The password could not be successfully validated.", UserException::INVALID_PASSWORD, $previous);
	}

}
