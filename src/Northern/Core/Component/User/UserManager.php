<?php

namespace Northern\Core\Component\User;

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
	 * Returns the UserEntity count.
	 *
	 * @param  string $status
	 * @return integer
	 */
	public function getUserEntityCount( $status = 'active' )
	{
		return $this->userRepository->getUserEntityCount( $status );
	}

	/**
	 * Returns an ArrayCollection of UserEntity's.
	 *
	 * @param  string $status
	 * @param  integer $offset
	 * @param  integer $limit
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getUserEntityCollection( $status = 'active', $offset = 0, $limit = 10 )
	{
		return $this->userRepository->getUserEntityCollection( $status, $offset, $limit );
	}

	/**
	 * Returns a UserEntity by the specified Id. If the user cannot be found an exception
	 * is thrown.
	 *
	 * @param  int $id
	 * @return \Northern\Core\Component\User\Entity\UserEntity
	 * @throws \Northern\Core\Component\User\Exception\UserNotFoundByIdException
	 */
	public function getUserEntityById( $id )
	{
		$userEntity = $this->userRepository->getUserEntityById( $id );

		if( empty( $userEntity ) )
		{
			throw new Exception\UserNotFoundByIdException( $id );
		}

		return $userEntity;
	}

	/**
	 * Returns a UserEntity by the specified email. If the user cannot be found an exception
	 * is thrown.
	 *
	 * @param  string $email
	 * @return \Northern\Core\Component\User\Entity\UserEntity
	 * @throws \Northern\Core\Component\User\Exception\UserNotFoundByEmailException
	 */
	public function getUserEntityByEmail( $email )
	{
		$userEntity = $this->userRepository->getUserEntityByEmail( $email );

		if( empty( $userEntity ) )
		{
			throw new Exception\UserNotFoundByEmailException( $email );
		}

		return $userEntity;
	}

	public function getUserEntityByAuthentication( $email, $plainTextPassword )
	{
		$userEntity = $this->getUserEntityByEmail( $email );

		$passwordSalt    = $userEntity->getSalt();
		$encodedPassword = $userEntity->getPassword();

		if( $this->passwordEncoder->isPasswordValid( $encodedPassword, $plainTextPassword, $passwordSalt ) == FALSE )
		{
			throw new Exception\InvalidPasswordException();
		}

		return $userEntity;
	}

	/**
	 * Creates a new UserEntity. The $email parameter must be unique, if 
	 * not this method will throw a UserValidationException.
	 *
	 * @param  string $email
	 * @param  string $password
	 * @return \Northern\Core\Component\User\Entity\UserEntity
	 * @throws \Northern\Core\Component\User\Exception\UserValidationException
	 */
	public function createUserEntity( $email, $password = NULL )
	{
		$userEntity = new Entity\UserEntity();

		$values = [
			'email' => $email,
		];

		if( ! empty( $password ) )
		{
			$values['password'] = $password;
		}

		$errors = $this->userValidator->validate( $values );

		$errors = $this->userValidator->validateUniqueEmail( $userEntity, $email, $errors );
		
		if( $errors->any() )
		{
			throw new Exception\UserValidationException( $errors );
		}

		if( ! empty( $password ) )
		{
			$userEntity->salt     = $this->passwordEncoder->generateSalt();
			$userEntity->password = $this->passwordEncoder->encodePassword( $password, $userEntity->salt );
		}

		$userEntity->email = $email;

		return $userEntity;
	}

	/**
	 * Updates an existing UserEntity.
	 *
	 * @param  \Northern\Core\Component\User\Entity\UserEntity
	 * @param  array $values
	 * @throws \Northern\Core\Component\User\Exception\UserValidationException
	 */
	public function updateUserEntity( Entity\UserEntity $userEntity, array $values )
	{
		$password        = Arr::extract( $values, 'password' );
		$passwordConfirm = Arr::extract( $values, 'passwordConfirm' );

		$errors = $this->userValidator->validate( $values );

		$errors = $this->userValidator->validateUniqueEmail( $userEntity, Arr::get( $values, 'email' ), $errors );

		if( ! empty( $password ) )
		{
			if( $password !== $passwordConfirm )
			{
				$errors->add('password', "The password and password confirm fields must be equal.");
			}
			else
			{
				$userEntity->salt     = $this->passwordEncoder->generateSalt();
				$userEntity->password = $this->passwordEncoder->encodePassword( $password, $userEntity->salt );
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
		$userEntity->update( $values );
	}

	/**
	 * Authenticates and existing user.
	 *
	 * @param  string $email
	 * @param  string $password
	 * @return Northern\Core\Component\User\Entity\UserEntity
	 * @throws Northern\Core\Component\User\Exception\InvalidPasswordException
	 */
	public function authenticateUser( $email, $password )
	{
		$userEntity = $this->getUserEntityByEmail( $email );

		if( ! $this->passwordEncoder->isPasswordValid( $userEntity->getPassword(), $password, $userEntity->getSalt() ) )
		{
			throw new Exception\InvalidPasswordException();
		}

		return $userEntity;
	}

	/**
	 * Creates a public token for the specified user. The public token can be used
	 * for password reset and other public user actions in which the user is required
	 * to be identified.
	 *
	 * @param  \Northern\Core\Component\Component\User\Entity\UserEntity
	 * @return string
	 */
	public function getUserEntityPublicToken( Entity\UserEntity $userEntity )
	{
		$publicToken = sha1("{$userEntity->getId()}{$userEntity->getEmail()}{$userEntity->getPassword()}{$userEntity->getTimeCreated()}");

		return $publicToken;
	}

}
