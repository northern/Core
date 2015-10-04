<?php

namespace Northern\Core\Common\Exception;

use Northern\Core\Common\Exception\Validation\Errors;

class ValidationException extends CoreException {

	protected $errors;
	protected $values;

	public function __construct( Errors $errors, array $values = array(), \Exception $previous = NULL )
	{
		parent::__construct("A validation exception occured.", static::$scope, $previous);

		$this->setErrors( $errors );
		$this->setValues( $values );
	}

	public function setErrors( Errors $errors )
	{
		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function setValues( array $values )
	{
		$this->values = $values;
	}

	public function getValues()
	{
		return $this->values;
	}

}
