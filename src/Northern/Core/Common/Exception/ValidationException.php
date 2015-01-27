<?php

namespace Northern\Core\Common\Exception;

use Northern\Core\Common\Exception\Validation\Errors;

class ValidationException extends CoreException {

	protected $errors;

	public function __construct( Errors $errors, \Exception $previous = NULL )
	{
		parent::__construct("A validation exception occured.", static::$scope, $previous);

		$this->setErrors( $errors );
	}

	public function setErrors( Errors $errors )
	{
		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}

}
