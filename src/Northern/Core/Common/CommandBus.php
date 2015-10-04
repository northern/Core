<?php

namespace Northern\Core\Common;

class CommandBus extends AbstractBase {

	protected $commands;

	public function __construct()
	{
		$this->commands = [];
	}

	public function __call( $method, $args )
	{
		$a = preg_split('/(?<=[a-z])(?=[A-Z])/x', $method);

		if( $a[0] == 'set' )
		{
			array_shift( $a );

			$commandName = lcfirst( implode( $a ) );
			
			if( ! isset( $this->commands[ $commandName ] ) )
			{
				$this->commands[ $commandName ] = $args[0];
			}
		}
		else
		{
			$command = "{$method}Command";

			if( ! isset( $this->commands[ $command ] ) )
			{
				throw new Exception\CommandBus\CommandNotFoundException( $command );
			}

			return call_user_func_array( array( $this->commands[ $command ],'execute' ), $args );
		}
	}

}
