<?php 

namespace Northern\Core\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Northern\Core\User\UserRepository")
 */
class UserEntity extends \Northern\Core\Common\Entity\AbstractEntity {
	
	const STATUS_ACTIVE   = 'active';
	const STATUS_DISABLED = 'disabled';
	const STATUS_DELETED  = 'deleted';
	
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
	 * @ORM\Column(type="string", length=128)
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

	public function __construct()
	{
		$this->setStatus( static::STATUS_ACTIVE );
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

}
