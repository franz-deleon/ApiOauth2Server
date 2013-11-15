<?php
namespace ApiOauth2Server\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="ApiOauth2Server\Model\Repository\OAuthClientRepository")
 * @ORM\Table(name="oauth_clients")
 */
class OAuthClient
{
    /**
     * @ORM\Id
     * @ORM\Column(name="client_id", type="integer")
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $clientId;

    /**
     * @ORM\ManyToMany(targetEntity="ApiOauth2Server\Model\Entity\OAuthUser", mappedBy="clients")
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="ApiOauth2Server\Model\Entity\OAuthRefreshToken", mappedBy="clientId", cascade={"remove"})
     * @var string
     */
    protected $refreshTokens;

    /**
     * @ORM\OneToMany(targetEntity="ApiOauth2Server\Model\Entity\OAuthAccessToken", mappedBy="clientId", cascade={"remove"})
     * @var string
     */
    protected $accessTokens;

    /**
     * @ORM\OneToMany(targetEntity="ApiOauth2Server\Model\Entity\OAuthScope", mappedBy="clientId", cascade={"remove"})
     * @var string
     */
    protected $scopes;

    /**
     * @ORM\Column(name="client_secret", type="string", length=100)
     * @var string
     */
    protected $clientSecret;

    /**
     * @ORM\Column(name="redirect_uri", type="string", length=255, nullable=true)
     * @var string
     */
    protected $redirectUri;

    /**
     * @ORM\Column(name="grant_types", type="string", length=50, nullable=true)
     * @var string
     */
    protected $grantTypes;

	/**
     * Constructor
     */
	public function __construct()
    {
        $this->userIds       = new ArrayCollection();
        $this->refreshTokens = new ArrayCollection();
        $this->accessTokens  = new ArrayCollection();
        $this->scope         = new ArrayCollection();
    }

    /**
     * @return the $clientId
     */
    public function getClientId()
    {
        return $this->clientId;
    }

	/**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

	/**
     * @return the $userIds
     */
    public function getUsers()
    {
        return $this->users;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $userIds
     */
    public function setUsers($users)
    {
        $this->users = $users;
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
     * @return the $clientSecret
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

	/**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

	/**
     * @return the $redirectUri
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

	/**
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

	/**
     * @return the $grantTypes
     */
    public function getGrantTypes()
    {
        return $this->grantTypes;
    }

	/**
     * @param string $grantTypes
     */
    public function setGrantTypes($grantTypes)
    {
        $this->grantTypes = $grantTypes;
    }
}
