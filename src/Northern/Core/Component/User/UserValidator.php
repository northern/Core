<?php

namespace Northern\Core\Component\User;

use Northern\Core\Domain\User;
use Northern\Core\Common\Exception\Validation\Errors;

use Northern\Common\Helper\ArrayHelper as Arr;

class UserValidator extends \Northern\Core\Common\AbstractValidator {
	
	protected $userRepository;

	public function setUserRepository( UserRepository $userRepository )
	{
		$this->userRepository = $userRepository;
	}

	public function getConstraints()
	{
		$constraints = array(
			'email' => array(
				array(
					'validator' => new \Zend\Validator\EmailAddress(),
					'message'   => "The email must be a valid email address.",
				),
			),
			'password' => array(
				array(
					'validator' => new \Zend\Validator\StringLength( array('min' => 6) ),
					'message'   => "The password must be at least 6 characters long.",
				),
			),
			'firstname' => array(
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
					'validator' => new \Zend\Validator\InArray( array('haystack' => User::getStatuses() ) ),
					'message'   => "Invalid status specified.",
				),
			),
		);

		return $constraints;
	}

	public function validateUniqueEmail( User $user, $email, Errors $errors = NULL )
	{
		if( empty( $errors ) )
		{
			$errors = new \Northern\Core\Common\Exception\Validation\Errors();
		}

		if( ! empty( $email ) )
		{
			if( $email !== $user->email )
			{
				$otherUser = $this->userRepository->getUserByEmail( $email );

				if( ! empty( $otherUser ) )
				{
					$errors->add('email', "There already is another user with this email address.");
				}
			}
		}

		return $errors;
	}

}
