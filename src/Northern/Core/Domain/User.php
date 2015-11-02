<?php 

namespace Northern\Core\Domain;

class User extends \Northern\Core\Common\AbstractObject {
	
	const STATUS_ACTIVE   = 'active';
	const STATUS_DISABLED = 'disabled';
	const STATUS_DELETED  = 'deleted';
	
	const ROLE_USER       = 'user';
	const ROLE_ADMIN      = 'admin';
	
	const FLAGS_VERIFIED  = 0x00000001;

	protected $id;
	protected $email;
	protected $password;
	protected $salt;
	protected $firstname;
	protected $lastname;
	protected $status;
	protected $role;
	protected $flags = 0;

	public function __construct()
	{
		$this
			->setStatus( static::STATUS_ACTIVE )
			->setRole( static::ROLE_USER )
		;
	}
	
	static public function getStatuses()
	{
		$statuses = [
			static::STATUS_ACTIVE,
			static::STATUS_DISABLED,
			static::STATUS_DELETED
		];

		return $statuses;
	}

	static public function  getRoles()
	{
		$roles = [
			static::ROLE_USER,
			static::ROLE_ADMIN,
		];

		return $roles;
	}

}
