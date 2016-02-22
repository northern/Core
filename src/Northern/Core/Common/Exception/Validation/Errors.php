<?php

namespace Northern\Core\Common\Exception\Validation;

use Northern\Common\Helper\ArrayHelper as Arr;

class Errors extends \ArrayObject {
	
	public function add( $key, $message )
	{
		$this[ $key ] = $message;
	}

	public function any()
	{
		return $this->count() > 0;
	}

	public function combine( Errors $errors )
	{
      return new Errors( Arr::merge( $this->getArrayCopy(), $errors->getArrayCopy() ) );
	}

   public function insert( $key, Errors $errors )
   {
      if( $errors->any() )
      {
         if( empty( $key ) )
         {
            $this->combine( $errors );
         }
         else
         {
            $this[ $key ] = $errors;
         }
      }
   }

}
