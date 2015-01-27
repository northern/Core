<?php

namespace Northern\Core\User\Exception;

abstract class UserException extends \Northern\Core\Common\Exception\CoreException {

	// INVALID = 100
	const INVALID_PASSWORD       = 101;

	// NOTFOUND = 200
	const NOTFOUND_BY_ID         = 201;
	const NOTFOUND_BY_EMAIL      = 202;

}
