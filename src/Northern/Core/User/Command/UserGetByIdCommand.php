<?php

namespace Northern\Core\User\Command;

class UserGetByIdCommand extends AbstractUserCommand {

	public function execute( $id )
	{
		$userEntity = $this->userManager->getUserEntityById( $id );
		
		return $userEntity;
	}

}
