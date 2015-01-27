<?php

namespace Northern\Core\Common\Database;

use Doctrine\ORM\EntityManager;

class EntityDatabase extends \Northern\Core\Common\AbstractDatabase {

	protected $em;

	public function setEntityManager( EntityManager $em )
	{
		$this->em = $em;
	}

	public function getEntityManager()
	{
		return $this->em;
	}

	public function save( $entities )
	{
		if( is_array( $entities ) )
		{
			foreach( $entities as $entity )
			{
				$this->save( $entity );
			}

			return;
		}

		$this->em->persist( $entities );
	}

	public function merge( $entity )
	{
		$this->em->merge( $entity );
	}

	public function delete( $entity )
	{
		$this->em->remove( $entity );
	}

	public function flush()
	{
		$this->em->flush();
	}

	public function beginTransaction()
	{
		$this->em->beginTransaction();
	}

	public function commit()
	{
		$this->em->commit();
	}

	public function rollback()
	{
		$this->em->rollback();
	}

}
