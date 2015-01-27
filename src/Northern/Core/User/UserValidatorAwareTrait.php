<?php

namespace Northern\Core\User;

trait UserValidatorAwareTrait {

	protected $userValidator;

	public function setUserValidator( UserValidator $userValidator )
	{
		$this->userValidator = $userValidator;
	}

}
