<?php

namespace Northern\Core\Common;

abstract class AbstractPersistent extends AbstractValue {

	protected function dateToTime( $date )
	{
		// If an invalid date was passed we return the current time.
		$time = time();
		
		if( $date instanceof \DateTime )
		{
			$time = $date->format('r');
		}
		else
		if( is_string( $date ) )
		{
			$time = strtotime( $date );
		}
		
		return $time;		
	}
	
	public function update( array $values = NULL )
	{
		if( ! empty( $values ) )
		{
			foreach( $values as $field => $value )
			{
				/*if( is_array( $value ) )
				{
					$entity = $this->__get( $field );
				}
				else
				{*/
					$this->__set( $field, $value );
				/*}*/
			}
		}
	}

}
