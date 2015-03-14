<?php 

namespace Northern\Core\Component\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Northern\Core\Component\User\UserRepository")
 */
class UserEntity extends \Northern\Core\Common\AbstractEntity {
	
	const STATUS_ACTIVE   = 'active';
	const STATUS_DISABLED = 'disabled';
	const STATUS_DELETED  = 'deleted';
	
	const ROLE_USER       = 'user';
	const ROLE_ADMIN      = 'admin';
	
	const FLAGS_VERIFIED  = 0x00000001;

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=64, unique=true)
	 */
	protected $email;
	
	/**
	 * @ORM\Column(type="string", length=128, nullable=true)
	 */
	protected $password;

	/**
	 * @ORM\Column(type="string", length=128, nullable=true)
	 */
	protected $salt;

	/**
	 * @ORM\Column(type="string", length=32, nullable=true)
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string", length=32, nullable=true)
	 */
	protected $lastname;
	
	/**
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	protected $status;

	/**
	 * @ORM\Column(type="string", length=16, nullable=false)
	 */
	protected $role;

	/**
	 * @ORM\Column(type="integer", nullable=false, options={"default"=0})
	 */
	protected $flags = 0;

	public function __construct()
	{
		$this
			->setStatus( static::STATUS_ACTIVE )
			->setRole( static::ROLE_USER )
		;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setEmail( $email )
	{
		$this->email = strtolower( $email );

		return $this;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setPassword( $password )
	{
		$this->password = $password;

		return $this;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getSalt()
	{
		return $this->salt;
	}

	public function setSalt( $salt )
	{
		$this->salt = $salt;

		return $this;
	}

	public function getFirstname()
	{
		return $this->firstname;
	}

	public function setFirstname( $firstname )
	{
		$this->firstname = $firstname;

		return $this;
	}

	public function getLastname()
	{
		return $this->lastname;
	}

	public function setLastname( $lastname )
	{
		$this->lastname = $lastname;

		return $this;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus( $status )
	{
		$this->status = $status;

		return $this;
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

	public function getRole()
	{
		return $this->role;
	}

	public function setRole( $role )
	{
		$this->role = $role;

		return $this;
	}

	static public function  getRoles()
	{
		$roles = [
			static::ROLE_USER,
			static::ROLE_ADMIN,
		];

		return $roles;
	}

	public function getFlags()
	{
		return $this->flags;
	}

	public function setFlags( $flags )
	{
		$this->flags = $flags;

		return $this;
	}

}
