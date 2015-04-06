<?php

namespace Northern\Core\Common;

abstract class AbstractValue {
	
	public function __set( $method, $value )
	{
		$method = "set".ucfirst( $method );

		if( method_exists( $this, $method ) )
		{
			$this->$method( $value );
		}
	}

	public function __get( $method )
	{
		$method = "get".ucfirst( $method );

		if( method_exists( $this, $method ) )
		{
			return $this->$method();
		}

		return NULL;
	}
	
}
