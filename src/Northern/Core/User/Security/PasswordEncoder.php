<?php

namespace Northern\Core\User\Security;

class PasswordEncoder extends \Northern\Core\Common\AbstractBase {

	public function generateSalt()
	{
		return NULL;
	}

	public function encodePassword( $plainTextPassword, $salt )
	{
		return password_hash( $plainTextPassword, PASSWORD_BCRYPT);
	}

	public function isPasswordValid( $encodedPassword, $plainTextPassword, $salt )
	{
		return password_verify( $plainTextPassword, $encodedPassword );
	}

}
