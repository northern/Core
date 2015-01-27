<?php

namespace Northern\Core\User;

trait UserRepositoryAwareTrait {

	public $userRepository;

	public function setUserRepository( UserRepository $userRepository )
	{
		$this->userRepository = $userRepository;
	}

}
