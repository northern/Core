<?php

namespace Northern\Core\Common;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractEntity {
	
	/**
	 * @ORM\Column(name="time_created", type="integer", options={"default" = 0})
	 */
	protected $timeCreated = 0;

	/**
	 * @ORM\Column(name="time_updated", type="integer", options={"default" = 0})
	 */
	protected $timeUpdated = 0;
	
	public function getTimeCreated()
	{
		return $this->timeCreated;
	}
	
	public function getTimeUpdated()
	{
		return $this->timeUpdated;
	}

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$time = time();
		
		$this->timeCreated = $time;
		$this->timeUpdated = 0;
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function onPreUpdate( \Doctrine\ORM\Event\PreUpdateEventArgs $eventArgs )
	{
		$this->timeUpdated = time();
	}
	
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
	
	public function invalidate()
	{
		$this->timeUpdated = time();
	}
	
	public function update( array $values )
	{
		foreach( $values as $field => $value )
		{
			$this->__set( $field, $value );
		}
	}

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