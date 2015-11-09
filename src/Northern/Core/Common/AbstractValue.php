<?php

namespace Northern\Core\Common;

abstract class AbstractValue {
	
	public function __set( $property, $value )
	{
		$method = "set".ucfirst( $property );

		if( method_exists( $this, $method ) )
		{
			$this->$method( $value );
		}
		else
		if( property_exists( get_class( $this ), $property ) )
		{
			$this->$property = $value;
		}
		else
		{
			throw new \RuntimeException("Cannot set property '{$property}' or method {$method} does not exist.");
		}
	}

	public function __get( $property )
	{
		$method = "get".ucfirst( $property );

		if( method_exists( $this, $method ) )
		{
			return $this->$method();
		}
		else
		if( property_exists( get_class( $this ), $property ) )
		{
			return $this->$property;
		}
		else
		{
			throw new \RuntimeException("Cannot get property '{$property}' or method {$method} does not exist.");
		}
	}

	public function __isset( $property )
	{
		return property_exists( get_class( $this ), $property );
	}

	public function __call( $method, array $arguments )
	{
		$accessorType = substr( $method, 0, 3 );

		$property = lcfirst( substr( $method, 3, strlen( $method ) - 3 ) );

		if( $accessorType === 'get' )
		{
			return $this->$property;
		}
		else
		if( $accessorType === 'set' )
		{
			$this->$property = $arguments[0];

			return $this;
		}

	}
	
}
