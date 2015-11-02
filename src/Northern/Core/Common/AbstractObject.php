<?php

namespace Northern\Core\Common;

use Doctrine\ORM\Event\PreUpdateEventArgs;

abstract class AbstractObject extends AbstractPersistent {
	
	protected $timeCreated = NULL;
	protected $timeUpdated = 0;
	
	public function getTimeCreated()
	{
		return $this->timeCreated === NULL ? 0 : $this->timeCreated;
	}
	
	public function getTimeUpdated()
	{
		return $this->timeUpdated;
	}

	public function onPrePersist()
	{
		if( $this->timeCreated === NULL )
		{
			$time = time();
			
			$this->timeCreated = $time;
			$this->timeUpdated = 0;
		}
	}
	
	public function onPreUpdate( PreUpdateEventArgs $eventArgs )
	{
		$this->timeUpdated = time();
	}
	
	public function invalidate()
	{
		$this->timeUpdated = time();
	}
	
}
