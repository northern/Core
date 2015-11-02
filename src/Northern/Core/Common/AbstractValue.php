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

		return $this;
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

		return NULL;
	}
	
}
