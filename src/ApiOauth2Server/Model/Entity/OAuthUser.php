<?php
namespace ApiOauth2Server\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="ApiOauth2Server\Model\Repository\OAuthUserRepository")
 * @ORM\Table(name="oauth_users")
 */
class OAuthUser
{
    /**
     * @ORM\Id
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $userId;

    /**
     * @ORM\ManyToMany(targetEntity="ApiOauth2Server\Model\Entity\OAuthClient", inversedBy="users", fetch="EAGER")
     * @ORM\JoinTable(name="users_clients",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="client_id")}
     * )
     */
    protected $clients;

    /**
     * @ORM\OneToMany(targetEntity="ApiOauth2Server\Model\Entity\OAuthRefreshToken", mappedBy="userId", cascade={"remove"})
     * @var string
     */
    protected $refreshTokens;

    /**
     * @ORM\OneToMany(targetEntity="ApiOauth2Server\Model\Entity\OAuthAccessToken", mappedBy="userId", cascade={"remove"})
     * @var string
     */
    protected $accessTokens;

    /**
     * @ORM\OneToMany(targetEntity="ApiOauth2Server\Model\Entity\OAuthScope", mappedBy="userId", cascade={"remove"})
     * @var string
     */
    protected $scopes;

    /**
     * @ORM\Column(name="user_name", type="string", unique=true, length=50, nullable=false)
     * @var string
     */
    protected $userName;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     * @var string
     */
    protected $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     * @var string
     */
    protected $lastName;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var string
     */
    protected $created;

	/**
     *Constructor
     */
	public function __construct()
    {
        $this->clientIds     = new ArrayCollection();
        $this->refreshTokens = new ArrayCollection();
        $this->accessTokens  = new ArrayCollection();
        $this->scope         = new ArrayCollection();
    }

    /**
     * @return the $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

	/**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

	/**
     * @return the $clientIds
     */
    public function getClients()
    {
        return $this->clients;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $clientIds
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    /**
     * @return the $refreshTokens
     */
    public function getRefreshTokens()
    {
        return $this->refreshTokens;
    }

	/**
     * @param string $refreshTokens
     */
    public function setRefreshTokens($refreshTokens)
    {
        $this->refreshTokens = $refreshTokens;
    }

	/**
     * @return the $accessTokens
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }

	/**
     * @param string $accessTokens
     */
    public function setAccessTokens($accessTokens)
    {
        $this->accessTokens = $accessTokens;
    }

	/**
     * @return the $userName
     */
    public function getUserName()
    {
        return $this->userName;
    }

	/**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

	/**
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

	/**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

	/**
     * @return the $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

	/**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

	/**
     * @return the $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

	/**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

	/**
     * @return the $created
     */
    public function getCreated()
    {
        return $this->created;
    }

	/**
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->created = "2013-NOV-12 12:21:12";
    }

}
