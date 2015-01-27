<?php

namespace Northern\Core\Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractEntity {
	
	/**
	 * @ORM\Column(name="time_created", type="integer")
	 */
	protected $timeCreated = 0;

	/**
	 * @ORM\Column(name="time_updated", type="integer")
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
	public function onPreUpdate()
	{
		$this->timeUpdated = time();
	}
	
	/**
	 * Converts a date string to a UTC time integer.
	 *
	 * @param  string $date
	 * @return integer
	 */
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
	
	/**
	 * Invalidates the entity and updated the 'timeUpdated' attribute.
	 */
	public function invalidate()
	{
		$this->timeUpdated = time();
	}
	
	public function update( array $values )
	{
		foreach( $values as $field => $value )
		{
			$this->$field = $value;
		}
	}

	/**
	 * Magic setter allows attribute access short cut.
	 *
	 * @param  string $method
	 * @param  mixed $value
	 */
	public function __set( $method, $value )
	{
		$method = "set".ucfirst( $method );

		if( method_exists( $this, $method ) )
		{
			$this->$method( $value );
		}
	}

	/**
	 * Magic getter allows attribute access short cut.
	 *
	 * @param  string $method
	 * @return mixed
	 */
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
