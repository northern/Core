<?php

namespace Northern\Core\Component\User\Exception;

class UserNotFoundByEmailException extends UserException {

	public function __construct( $email, \Exception $previous = NULL )
	{
		parent::__construct("The user with email \"{$email}\" could not be found.", UserException::NOTFOUND_BY_EMAIL, $previous);
	}

}
