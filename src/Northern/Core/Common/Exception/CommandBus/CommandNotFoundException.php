<?php

namespace Northern\Core\Common\Exception\CommandBus;

class CommandNotFoundException extends \Northern\Core\Common\Exception\CoreException {
	
	public function __construct( $command, \Exception $previous = NULL )
	{
		parent::__construct("The command {$command} could be executed.", 0, $previous);
	}

}
