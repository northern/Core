<?php

namespace Northern\Core\Common;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractEntity extends AbstractPersistent {
	
	/**
	 * @ORM\Column(name="time_created", type="integer", options={"default" = 0})
	 */
	protected $timeCreated = NULL;

	/**
	 * @ORM\Column(name="time_updated", type="integer", options={"default" = 0})
	 */
	protected $timeUpdated = 0;
	
	public function getTimeCreated()
	{
		return $this->timeCreated === NULL ? 0 : $this->timeCreated;
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
		if( $this->timeCreated === NULL )
		{
			$time = time();
			
			$this->timeCreated = $time;
			$this->timeUpdated = 0;
		}
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function onPreUpdate( \Doctrine\ORM\Event\PreUpdateEventArgs $eventArgs )
	{
		$this->timeUpdated = time();
	}
	
	public function invalidate()
	{
		$this->timeUpdated = time();
	}
	
}
