<?php

namespace Northern\Core\Common\Exception;

use Northern\Core\Common\Exception\Validation\Errors;

class ValidationException extends CoreException {

	use ValidationExceptionAwareTrait;

	public function __construct( Errors $errors, array $values = array(), \Exception $previous = NULL )
	{
		parent::__construct("A validation exception occured.", static::$scope, $previous);

		$this->setErrors( $errors );
		$this->setValues( $values );
	}

}
