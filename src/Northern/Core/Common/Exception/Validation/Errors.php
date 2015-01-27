<?php

namespace Northern\Core\Common\Exception\Validation;

class Errors extends \ArrayObject {
	
	public function add( $key, $message )
	{
		$this[ $key ] = $message;
	}

	public function any()
	{
		return $this->count() > 0;
	}

}
