<?php

namespace Northern\Core\Common;

abstract class AbstractValidator extends AbstractBase {

	public function getConstraints()
	{
		return array();
	}

	public function validate( array $values, Errors $errors = NULL )
	{
		if( empty( $errors ) )
		{
			$errors = new \Northern\Core\Common\Exception\Validation\Errors();
		}

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
					$errors->add( $field, $rule['message'] );
					break;
				}
			}
		}

		return $errors;
	}

}
