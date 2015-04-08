<?php

namespace Northern\Core\Component\User\Security;

interface PasswordEncoderInterface {

	public function generateSalt();
	public function encodePassword( $plainTextPassword, $salt );
	public function isPasswordValid( $encodedPassword, $plainTextPassword, $salt );

}
