<?php

namespace Northern\Core\Component\User;

use Northern\Core\Domain\User;
use Northern\Common\Helper\ArrayHelper as Arr;

class UserManager extends \Northern\Core\Common\AbstractManager {

	use Security\PasswordEncoderAwareTrait;

	protected $userValidator;
	protected $userRepository;

	/**
	 * Injects a UserValidator.
	 *
	 * @param \Northern\Core\Component\User\UserValidator $userValidator
	 */
	public function setUserValidator( UserValidator $userValidator )
	{
		$this->userValidator = $userValidator;
	}

	/**
	 * Injects a UserRepository.
	 *
	 * @param \Northern\Core\Component\User\UserRepository $userRepository
	 */
	public function setUserRepository( UserRepository $userRepository )
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * Returns the User count.
	 *
	 * @param  string $status
	 * @return integer
	 */
	public function getUserCount( $status = 'active' )
	{
		return $this->userRepository->getUserCount( $status );
	}

	/**
	 * Returns an ArrayCollection of User's.
	 *
	 * @param  string $status
	 * @param  integer $offset
	 * @param  integer $limit
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getUserCollection( $status = 'active', $offset = 0, $limit = 10 )
	{
		return $this->userRepository->getUserCollection( $status, $offset, $limit );
	}

	/**
	 * Returns a User by the specified Id. If the user cannot be found an exception
	 * is thrown.
	 *
	 * @param  int $id
	 * @return \Northern\Core\Domain\User
	 * @throws \Northern\Core\Component\User\Exception\UserNotFoundByIdException
	 */
	public function getUserById( $id )
	{
		$user = $this->userRepository->getUserById( $id );

		if( empty( $user ) )
		{
			throw new Exception\UserNotFoundByIdException( $id );
		}

		return $user;
	}

	/**
	 * Returns a User by the specified email. If the user cannot be found an exception
	 * is thrown.
	 *
	 * @param  string $email
	 * @return \Northern\Core\Domain\UserEntity
	 * @throws \Northern\Core\Component\User\Exception\UserNotFoundByEmailException
	 */
	public function getUserByEmail( $email )
	{
		$user = $this->userRepository->getUserByEmail( $email );

		if( empty( $user ) )
		{
			throw new Exception\UserNotFoundByEmailException( $email );
		}

		return $user;
	}

	public function getUserByAuthentication( $email, $plainTextPassword )
	{
		$user = $this->getUserByEmail( $email );

		$passwordSalt    = $user->getSalt();
		$encodedPassword = $user->getPassword();

		if( $this->passwordEncoder->isPasswordValid( $encodedPassword, $plainTextPassword, $passwordSalt ) == FALSE )
		{
			throw new Exception\InvalidPasswordException();
		}

		return $user;
	}

	/**
	 * Creates a new User. The $email parameter must be unique, if 
	 * not this method will throw a UserValidationException.
	 *
	 * @param  string $email
	 * @param  string $password
	 * @return \Northern\Core\Domain\User
	 * @throws \Northern\Core\Component\User\Exception\UserValidationException
	 */
	public function createUser( $email, $password = NULL )
	{
		$user = new User();

		$values = [
			'email' => $email,
		];

		if( ! empty( $password ) )
		{
			$values['password'] = $password;
		}

		$errors = $this->userValidator->validate( $values );

		$errors = $this->userValidator->validateUniqueEmail( $user, $email, $errors );
		
		if( $errors->any() )
		{
			throw new Exception\UserValidationException( $errors );
		}

		if( ! empty( $password ) )
		{
			$user->salt     = $this->passwordEncoder->generateSalt();
			$user->password = $this->passwordEncoder->encodePassword( $password, $user->salt );
		}

		$user->email = $email;

		return $user;
	}

	/**
	 * Updates an existing User.
	 *
	 * @param  \Northern\Core\Domain\User
	 * @param  array $values
	 * @throws \Northern\Core\Component\User\Exception\UserValidationException
	 */
	public function updateUser( User $user, array $values )
	{
		$password        = Arr::extract( $values, 'password', NULL );
		$passwordConfirm = Arr::extract( $values, 'passwordConfirm', NULL );

		$errors = $this->userValidator->validate( $values );

		$errors = $this->userValidator->validateUniqueEmail( $user, Arr::get( $values, 'email' ), $errors );

		if( $password !== NULL AND ( ! empty( $password ) AND ! empty( $passwordConfirm ) ) )
		{
			if( empty( $password ) )
			{
				$errors->add('password', "The password cannot be left blank.");
			}
			else
			if( empty( $passwordConfirm ) )
			{
				$errors->add('password', "The password confirm cannot be left blank.");
			}
			else
			if( $password !== $passwordConfirm )
			{
				$errors->add('password', "The password and password confirm fields must be equal.");
			}
			else
			{
				$user->salt     = $this->passwordEncoder->generateSalt();
				$user->password = $this->passwordEncoder->encodePassword( $password, $user->salt );
			}
		}

		if( $errors->any() )
		{
			throw new Exception\UserValidationException( $errors );
		}

		// Remove the 'password' field here so it's not set overwritten during the
		// entity 'update' method. In fact, in any situation we need to remove the
		// 'password' field here. We remove the passwordConfirm field for consistency.
		unset( $values['password'] );
		unset( $values['passwordConfirm'] );

		// Update the user entity with the new validated values.
		$user->update( $values );
	}

	/**
	 * Authenticates and existing User.
	 *
	 * @param  string $email
	 * @param  string $password
	 * @return Northern\Core\Domain\User
	 * @throws Northern\Core\Component\User\Exception\InvalidPasswordException
	 */
	public function authenticateUser( $email, $password )
	{
		$user = $this->getUserByEmail( $email );

		if( ! $this->passwordEncoder->isPasswordValid( $user->getPassword(), $password, $user->getSalt() ) )
		{
			throw new Exception\InvalidPasswordException();
		}

		return $user;
	}

	/**
	 * Creates a public token for the specified User. The public token can be used
	 * for password reset and other public user actions in which the user is required
	 * to be identified.
	 *
	 * @param  \Northern\Core\Domain\User
	 * @return string
	 */
	public function getUserPublicToken( User $user )
	{
		$publicToken = sha1("{$user->getId()}{$user->getEmail()}{$user->getPassword()}{$user->getTimeCreated()}");

		return $publicToken;
	}

}
