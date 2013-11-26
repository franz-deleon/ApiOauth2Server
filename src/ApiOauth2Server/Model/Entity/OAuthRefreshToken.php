<?php
namespace ApiOauth2Server\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ApiOauth2Server\Model\Repository\OAuthRefreshTokenRepository")
 * @ORM\Table(name="oauth_refresh_tokens")
 */
class OAuthRefreshToken
{
    /**
     * Refresh token valid 'used' values
     * @var string
     */
    const USED_YES = 'yes';
    const USED_NO  = 'no';

    /**
     * @ORM\Id
     * @ORM\Column(name="refresh_token", type="string", length=100, nullable=false)
     * @var string
     */
    protected $refreshToken;

    /**
     * @ORM\ManyToOne(targetEntity="ApiOauth2Server\Model\Entity\OAuthClient", inversedBy="refreshTokens", fetch="EAGER")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="client_id")
     * @var string
     */
    protected $clientId;

    /**
     * @ORM\ManyToOne(targetEntity="ApiOauth2Server\Model\Entity\OAuthUser", inversedBy="refreshTokens", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * @var int
     */
    protected $userId;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var string
     */
    protected $expires;

    /**
     * @ORM\Column(type="enumyesno", nullable=false)
     * @var string
     */
    protected $used;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $scope;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @ORM\Version
     * @var string
     */
    protected $created;

	/**
     * @return the $refreshToken
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

	/**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

	/**
     * @return the $clientId
     */
    public function getClientId()
    {
        return $this->clientId;
    }

	/**
     * @param field_type $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

	/**
     * @return the $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

	/**
     * @param field_type $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

	/**
     * @return the $expires
     */
    public function getExpires()
    {
        return $this->expires;
    }

	/**
     * @param \ApiOauth2Server\Model\Entity\unknown $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

	/**
     * @return the $used
     */
    public function getUsed()
    {
        return $this->used;
    }

	/**
     * @param number $used
     */
    public function setUsed($used)
    {
        $this->used = $used;
        return $this;
    }

	/**
     * @return the $scope
     */
    public function getScope()
    {
        return $this->scope;
    }

	/**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
}
