<?php

namespace Northern\Core\Component\User\Security;

trait PasswordEncoderAwareTrait {

	protected $passwordEncoder;

	public function setPasswordEncoder( PasswordEncoderInterface $passwordEncoder )
	{
		$this->passwordEncoder = $passwordEncoder;
	}

}
