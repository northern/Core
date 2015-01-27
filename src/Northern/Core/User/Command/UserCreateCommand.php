<?php

namespace Northern\Core\User\Command;

class UserCreateCommand extends AbstractUserCommand {

	public function execute( $email, $password )
	{
		$this->database->beginTransaction();

		try
		{
			$userEntity = $this->userManager->createUserEntity( $email, $password );

			$this->database->save( $userEntity );
			$this->database->commit();
			$this->database->flush();
		}
		catch( \Exception $e )
		{
			$this->database->rollback();

			throw $e;
		}
		
		return $userEntity;
	}

}
