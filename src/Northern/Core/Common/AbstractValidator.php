<?php

namespace Northern\Core\Common;

abstract class AbstractValidator extends AbstractBase {

	protected $errors;

	public function getErrors()
	{
		return $this->errors;
	}

	public function getConstraints()
	{
		return array();
	}

	public function validate( array $values )
	{
		$this->errors = new \Northern\Core\Common\Exception\Validation\Errors();

		$constraints = $this->getConstraints();
		
		foreach( $values as $field => $value )
		{
			if( ! isset( $constraints[ $field ] ) )
			{
				continue;
			}

			$rules = $constraints[ $field ];

			foreach( $rules as $rule )
			{
				if( ! $rule['validator']->isValid( $value ) )
				{
					$this->errors->add( $field, $rule['message'] );
					break;
				}
			}
		}

		return $this->errors;
	}

}
