<?php

namespace Northern\Core\Common;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class AbstractRepository extends EntityRepository {
	
	use \Psr\Log\LoggerAwareTrait;

	/**
	 * Returns an instance the entity manager.
	 *
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getEntityManager()
	{
		return $this->_em;
	}

	/**
	 * Returns an instance of a clean entity manager.
	 * 
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getCleanEntityManager()
	{
		$em = $this->getEntityManager();
		$em->clear();
		
		return $em;
	}
	
	public function getPaginator( $query )
	{
		$paginator  = new Paginator( $query );
		
		return $paginator;
	}

   public function persist( $entity )
   {
      $em = $this->getEntityManager();
      $em->persist( $entity );
   }

   public function flush()
   {
      $em = $this->getEntityManager();
      $em->flush();
   }

}
