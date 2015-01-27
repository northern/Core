<?php

namespace Northern\Core\User\Command;

class UserGetByEmailCommand extends AbstractUserCommand {

	public function execute( $email )
	{
		$userEntity = $this->userManager->getUserEntityByEmail( $email );
		
		return $userEntity;
	}

}
