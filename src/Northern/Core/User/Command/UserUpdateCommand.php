<?php

namespace Northern\Core\User\Command;

use Northern\Core\User\Entity\UserEntity;

class UserUpdateCommand extends AbstractUserCommand {

	public function execute( UserEntity $userEntity, array $values )
	{
		$this->database->beginTransaction();

		try
		{
			$userEntity = $this->userManager->updateUserEntity( $userEntity, $values );

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
