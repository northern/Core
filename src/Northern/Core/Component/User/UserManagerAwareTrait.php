<?php

namespace Northern\Core\Component\User;

trait UserManagerAwareTrait {

	protected $userManager;

	public function setUserManager( UserManager $userManager )
	{
		$this->userManager = $userManager;
	}

}
