<?php

namespace Northern\Core\Common\Exception;

use Northern\Core\Common\Exception\Validation\Errors;

trait ValidationExceptionAwareTrait {

	protected $errors;
	protected $values;

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
