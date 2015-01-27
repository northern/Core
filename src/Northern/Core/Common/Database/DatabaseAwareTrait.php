<?php

namespace Northern\Core\Common\Database;

trait DatabaseAwareTrait {

	protected $database;

	public function setDatabase( \Northern\Core\Common\AbstractDatabase $database )
	{
		$this->database = $database;
	}
	
}