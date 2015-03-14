<?php

namespace Northern\Core\Component\User;

class UserManagerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Tests when a user is loaded by it's Id that the correct entity object is returned.
	 */
	public function testGetUserEntityByIdSuccessful()
	{
		$testUserEntity = new Entity\UserEntity();

		$mockUserRepository = $this->getMockUserRepository();
		$mockUserRepository
			->method('getUserEntityById')
				->will( $this->returnValue( $testUserEntity ) )
		;

		$userManager = new UserManager();
		$userManager->setUserRepository( $mockUserRepository );

		$user = $userManager->getUserEntityById( 1 );
		
		$this->assertEquals( $user, $testUserEntity );
	}

	/**
	 * Tests when a user cannot be loaded by it Id that an exception is thrown.
	 *
	 * @expectedException \Northern\Core\Component\User\Exception\UserNotFoundByIdException
	 */
	public function testGetUserEntityByIdFail()
	{
		$mockUserRepository = $this->getMockUserRepository();
		$mockUserRepository
			->method('getUserEntityById')
				->will( $this->returnValue( NULL ) )
		;

		$userManager = new UserManager();
		$userManager->setUserRepository( $mockUserRepository );

		$user = $userManager->getUserEntityById( 1 );
	}

	/**
	 * Tests when a user is loaded by it's email address that the correct entity 
	 * object is returned.
	 */
	public function testGetUserEntityByEmailSuccessful()
	{
		$testUserEntity = new Entity\UserEntity();

		$mockUserRepository = $this->getMockUserRepository();
		$mockUserRepository
			->method('getUserEntityByEmail')
				->will( $this->returnValue( $testUserEntity ) )
		;

		$userManager = new UserManager();
		$userManager->setUserRepository( $mockUserRepository );

		$user = $userManager->getUserEntityByEmail('dummy@example.com');
		
		$this->assertEquals( $user, $testUserEntity );
	}

	/**
	 * Tests when a user cannot be loaded by it email address that an exception 
	 * is thrown.
	 *
	 * @expectedException \Northern\Core\Component\User\Exception\UserNotFoundByEmailException
	 */
	public function testGetUserEntityByEmailFail()
	{
		$mockUserRepository = $this->getMockUserRepository();
		$mockUserRepository
			->method('getUserEntityByEmail')
				->will( $this->returnValue( NULL ) )
		;

		$userManager = new UserManager();
		$userManager->setUserRepository( $mockUserRepository );

		$user = $userManager->getUserEntityByEmail('dummy@example.com');
	}

	/**
	 * Tests the user count.
	 */
	public function testGetUserEntityCountSuccessful()
	{
		$expectedUserEntityCount = 5;

		$mockUserRepository = $this->getMockUserRepository();
		$mockUserRepository
			->method('getUserEntityCount')
				->will( $this->returnValue( $expectedUserEntityCount ) )
		;

		$userManager = new UserManager();
		$userManager->setUserRepository( $mockUserRepository );

		$userEntityCount = $userManager->getUserEntityCount();

		$this->assertEquals( $userEntityCount, $expectedUserEntityCount );
	}

	/**
	 * Tests if a returned collection of UserEntity objects is of the correct type and
	 * of the correct length.
	 */
	public function testGetUserEntityCollectionSucessful()
	{
		$expectedUserEntityCollection = new \Doctrine\Common\Collections\ArrayCollection();

		$expectedUserEntityCollection->add( new Entity\UserEntity() );
		$expectedUserEntityCollection->add( new Entity\UserEntity() );
		$expectedUserEntityCollection->add( new Entity\UserEntity() );

		$mockUserRepository = $this->getMockUserRepository();
		$mockUserRepository
			->method('getUserEntityCollection')
				->will( $this->returnValue( $expectedUserEntityCollection ) )
		;

		$userManager = new UserManager();
		$userManager->setUserRepository( $mockUserRepository );

		$userEntityCollection = $userManager->getUserEntityCollection();

		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $userEntityCollection);

		$this->assertEquals( count( $userEntityCollection ), count( $expectedUserEntityCollection ) );
	}

	/**
	 * Tests if a UserEntity can be successfully created.
	 */
	public function testCreateUserEntitySuccessful()
	{
		$expectedEmailAddress = 'dummy@example.com';

		$expectedErrors = new \Northern\Core\Common\Exception\Validation\Errors();

		$mockUserValidator = $this->getMockUserValidator();
		$mockUserValidator
			->method('validateUniqueEmail')
				->will( $this->returnValue( $expectedErrors ) )
		;

		$mockPasswordEncoder = $this->getMockPasswordEncoder();
		$mockPasswordEncoder
			->method('generateSalt')
				->will( $this->returnValue( NULL ) )
		;
		$mockPasswordEncoder
			->method('encodePassword')
				->will( $this->returnValue( 's3cr3t') )
		;

		$userManager = new UserManager();
		$userManager->setUserValidator( $mockUserValidator );
		$userManager->setPasswordEncoder( $mockPasswordEncoder );

		$userEntity = $userManager->createUserEntity( $expectedEmailAddress, 's3cr3t' );

		$this->assertInstanceOf('Northern\Core\Component\User\Entity\UserEntity', $userEntity);

		$this->assertEquals( $expectedEmailAddress, $userEntity->getEmail() );
	}

	/**
	 * 
	 * @expectedException \Northern\Core\Component\User\Exception\UserValidationException
	 */
	public function testCreateUserEntityFail()
	{
		$expectedEmailAddress = 'dummy@example.com';

		$expectedErrors = new \Northern\Core\Common\Exception\Validation\Errors();
		$expectedErrors->add('email', "There already is another user with this email address.");

		$mockUserValidator = $this->getMockUserValidator();
		$mockUserValidator
			->method('validateUniqueEmail')
				->will( $this->returnValue( $expectedErrors ) )
		;

		$userManager = new UserManager();
		$userManager->setUserValidator( $mockUserValidator );

		$userEntity = $userManager->createUserEntity( $expectedEmailAddress );
	}

	public function testUpdateUserEntitySuccessful()
	{
		$expectedEmailAddress = 'dummy@example.com';
		$expectedPassword     = 'qwerty';

		$testUserEntity = new Entity\UserEntity();
		$testUserEntity->setEmail( $expectedEmailAddress );

		$expectedErrors = new \Northern\Core\Common\Exception\Validation\Errors();

		$mockUserValidator = $this->getMockUserValidator();
		$mockUserValidator
			->method('validate')
				->will( $this->returnValue( $expectedErrors ) )
		;
		$mockUserValidator = $this->getMockUserValidator();
		$mockUserValidator
			->method('validateUniqueEmail')
				->will( $this->returnValue( $expectedErrors ) )
		;

		$mockPasswordEncoder = $this->getMockPasswordEncoder();
		$mockPasswordEncoder
			->method('generateSalt')
				->will( $this->returnValue( NULL ) )
		;
		$mockPasswordEncoder
			->method('encodePassword')
				->will( $this->returnValue( $expectedPassword ) )
		;

		$userManager = new UserManager();
		$userManager->setUserValidator( $mockUserValidator );
		$userManager->setPasswordEncoder( $mockPasswordEncoder );

		$values = [
			'email'     => $expectedEmailAddress,
			'firstname' => 'John',
			'lastname'  => 'Doe',
			'password'  => $expectedPassword,
			'passwordConfirm' => $expectedPassword,
		];

		$userManager->updateUserEntity( $testUserEntity, $values );

		$this->assertEquals( $testUserEntity->getEmail(), $expectedEmailAddress );
		$this->assertEquals( $testUserEntity->getFirstname(), $values['firstname'] );
		$this->assertEquals( $testUserEntity->getLastname(), $values['lastname'] );
		$this->assertEquals( $testUserEntity->getPassword(), $expectedPassword );
	}

	/*public function testUpdateUserEntityChangeEmailSuccessful()
	{
		$expectedEmailAddress = 'dummy@example.com';

		$testUserEntity = new Entity\UserEntity();
		$testUserEntity->setEmail( $expectedEmailAddress );

		$expectedErrors = new \Northern\Core\Common\Exception\Validation\Errors();

		$mockUserRepository = $this->getMockUserRepository();
		$mockUserRepository
			->expects( $this->any() )
			->method('getUserEntityByEmail')
				->will( $this->returnValue( NULL ) )
		;

		$mockUserValidator = $this->getMockUserValidator();
		$mockUserValidator
			->expects( $this->any() )
			->method('validate')
				->will( $this->returnValue( $expectedErrors ) )
		;

		$userManager = new UserManager();
		$userManager->setUserRepository( $mockUserRepository );
		$userManager->setUserValidator( $mockUserValidator );

		$values = [];

		$userManager->updateUserEntity( $testUserEntity, $values );

		$this->assertEquals( $testUserEntity->getEmail(), $expectedEmailAddress );


	}*/

	/**
	 * Returns a mock UserRepository object.
	 */
	protected function getMockUserRepository()
	{
		$mockUserRepository = $this->getMockBuilder('Northern\Core\Component\User\UserRepository')
			->disableOriginalConstructor()
			->getMock()
		;

		return $mockUserRepository;
	}

	protected function getMockUserValidator()
	{
		$mockUserValidator = $this->getMockBuilder('Northern\Core\Component\User\UserValidator')
			->disableOriginalConstructor()
			->getMock()
		;

		return $mockUserValidator;
	}

	protected function getMockPasswordEncoder()
	{
		$mockPasswordEncoder = $this->getMockBuilder('Northern\Core\Component\User\Security\PasswordEncoder')
			->disableOriginalConstructor()
			->getMock()
		;

		return $mockPasswordEncoder;
	}

}
