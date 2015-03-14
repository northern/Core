<?php

namespace Northern\Core\Component\User\Exception;

class UserNotFoundByIdException extends UserException {

	public function __construct( $id, \Exception $previous = NULL )
	{
		parent::__construct("The user with Id {$id} could not be found.", UserException::NOTFOUND_BY_ID, $previous);
	}

}
