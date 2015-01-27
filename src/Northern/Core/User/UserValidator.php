<?php

namespace Northern\Core\User;

class UserValidator extends \Northern\Core\Common\AbstractValidator {
	
	public function getConstraints()
	{
		$constraints = array(
			'email' => array(
				array(
					'validator' => new \Zend\Validator\EmailAddress(),
					'message'   => "The email must be a valid email address.",
				),
			),
			/*'password' => array(
				array(
					'validator' => new \Zend\Validator\StringLength( array('min' => 6) ),
					'message'   => "The password must be at least 6 characters long.",
				),
			),*/
			/*'firstname' => array(
				array(
					'validator' => new \Zend\Validator\StringLength( array( 'max' => 32) ),
					'message'   => "The first name cannot be longer that 32 characters",
				),
			),
			'lastname' => array(
				array(
					'validator' => new \Zend\Validator\StringLength( array('max' => 32) ),
					'message'   => "The last name cannot be longer that 32 characters",
				),
			),
			'status' => array(
				array(
					'validator' => new \Zend\Validator\InArray( array('haystack' => \Listbox\Core\User\Entity\UserEntity::getStatuses() ) ),
					'message'   => "Invalid status specified.",
				),
			),*/
		);

		return $constraints;
	}

}
