<?php

namespace Northern\Core\User;

use Northern\Common\Helper\ArrayHelper as Arr;

class UserManager extends \Northern\Core\Common\AbstractManager {

	use \Northern\Core\User\UserValidatorAwareTrait;
	use \Northern\Core\User\UserRepositoryAwareTrait;
	use \Northern\Core\User\Security\PasswordEncoderInjectionTrait;

	public function getUserEntityCount( $status = 'active' )
	{
		return $this->userRepository->getUserEntityCount( $status );
	}

	public function getUserEntityCollection( $status = 'active', $offset = 0, $limit = 10 )
	{
		return $this->userRepository->getUserEntityCollection( $status, $offset, $limit );
	}

	/**
	 * Returns a UserEntity by the specified Id. If the user cannot be found an exception
	 * is thrown.
	 *
	 * @param  int $id
	 * @return \Northern\Core\User\Entity\UserEntity
	 * @throws \Northern\Core\User\Exception\UserNotFoundByIdException
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
	 * @return \Northern\Core\User\Entity\UserEntity
	 * @throws \Northern\Core\User\Exception\UserNotFoundByEmailException
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

	/**
	 * Creates a new UserEntity. The $email parameter must be unique, if 
	 * not this method will throw a UserValidationException.
	 *
	 * @param  string $email
	 * @param  string $plainTextPassword
	 * @return \Northern\Core\User\Entity\UserEntity
	 * @throws \Northern\Core\User\Exception\UserValidationException
	 */
	public function createUserEntity( $email, $plainTextPassword )
	{
		$values = array(
			'email'           => $email,
			'password'        => $plainTextPassword,
			'passwordConfirm' => $plainTextPassword,
		);

		$userEntity = new Entity\UserEntity();
		$userEntity = $this->updateUserEntity( $userEntity, $values );

		return $userEntity;
	}

	/**
	 * Updates an existing UserEntity.
	 *
	 * @param  \Northern\Core\User\Entity\UserEntity
	 * @param  array $values
	 * @return \Northern\Core\User\Entity\UserEntity
	 * @throws \Northern\Core\User\Exception\UserValidationException
	 */
	public function updateUserEntity( Entity\UserEntity $userEntity, array $values )
	{
		$password        = Arr::get( $values, 'password' );
		$passwordConfirm = Arr::get( $values, 'passwordConfirm' );

		$errors = $this->userValidator->validate( $values );

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
		// 'password' field here.
		unset( $values['password'] );
		unset( $values['passwordConfirm'] );

		$userEntity->update( $values );

		return $userEntity;
	}

	/**
	 * Authenticates and existing user.
	 *
	 * @param  string $email
	 * @param  string $password
	 * @return Northern\Core\User\Entity\UserEntity
	 * @throws Northern\Core\User\Exception\InvalidPasswordException
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

}
