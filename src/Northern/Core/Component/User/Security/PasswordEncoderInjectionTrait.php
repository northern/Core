<?php

namespace Northern\Core\Component\User\Security;

trait PasswordEncoderInjectionTrait {

	protected $passwordEncoder;

	public function setPasswordEncoder( PasswordEncoder $passwordEncoder )
	{
		$this->passwordEncoder = $passwordEncoder;
	}

}
