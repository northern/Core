<?php

namespace Northern\Core\User;

trait UserManagerAwareTrait {

	protected $userManager;

	public function setUserManager( UserManager $userManager )
	{
		$this->userManager = $userManager;
	}

}
