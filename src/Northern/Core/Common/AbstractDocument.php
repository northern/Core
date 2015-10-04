<?php

namespace Northern\Core\Common;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\MappedSuperclass
 * @ODM\HasLifecycleCallbacks()
 */
abstract class AbstractDocument extends AbstractPersistent {

	/**
 	 * @ODM\Int
	 */
	protected $timeCreated = 0;

	/**
 	 * @ODM\Int
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
	 * @ODM\PrePersist
	 */
	public function onPrePersist()
	{
		$time = time();
		
		$this->timeCreated = $time;
		$this->timeUpdated = 0;
	}
	
	/**
	 * @ODM\PreUpdate
	 */
	public function onPreUpdate()
	{
		$this->timeUpdated = time();
	}
	
	public function invalidate()
	{
		$this->timeUpdated = time();
	}

}
