<?php

namespace Northern\Core\Component\User;

use Doctrine\Common\Collections\ArrayCollection;

class UserRepository extends \Northern\Core\Common\AbstractRepository {

	public function getUserCount( $status )
	{
		$query = $this->getUserCollectionQuery( $status, NULL, NULL );

		$paginator = $this->getPaginator( $query );

		return count( $paginator );
	}

	public function getUserCollection( $status, $offset, $limit )
	{
		$query = $this->getUserCollectionQuery( $status, $offset, $limit );

		$paginator = $this->getPaginator( $query );

		$result = $query->getResult();

		$userCollection = new ArrayCollection( $result );

		return $userCollection;
	}

	public function getUserById( $id )
	{
		return $this->findOneById( $id );
	}

	public function getUserByName( $name )
	{
		return $this->findOneByName( $name );
	}

	public function getUserByEmail( $email )
	{
		return $this->findOneByEmail( $email );
	}

	protected function getUserCollectionQuery( $status, $offset, $limit )
	{
		$em = $this->getEntityManager();

		$entityName = $this->getEntityName();

		$qb = $em->createQueryBuilder();
		$qb
			->select( array('u') )
			->from( $entityName /*'\Northern\Core\Component\User\Entity\UserEntity'*/, 'u' )
		;

		if( $status !== NULL )
		{
			$qb->where('u.status = :status');
		}

		$query = $qb->getQuery();

		if( $status !== NULL )
		{
			$query->setParameter('status', $status);
		}

		if( $offset !== NULL AND $limit !== NULL )
		{
			$query->setFirstResult( $offset );
			$query->setMaxResults( $limit );
		}

		return $query;		
	}

}
