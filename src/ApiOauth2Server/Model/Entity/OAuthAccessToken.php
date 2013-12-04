<?php
namespace ApiOauth2Server\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ApiOauth2Server\Model\Repository\OAuthAccessTokenRepository")
 * @ORM\Table(name="oauth_access_tokens")
 */
class OAuthAccessToken
{
    /**
     * @ORM\Id
     * @ORM\Column(name="access_token", type="string", unique=true, nullable=false)
     * @var string
     */
    protected $accessToken;

    /**
     * @ORM\ManyToOne(targetEntity="ApiOauth2Server\Model\Entity\OAuthClient", inversedBy="accessTokens")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="client_id")
     * @var string
     */
    protected $clientId;

    /**
     * @ORM\ManyToOne(targetEntity="ApiOauth2Server\Model\Entity\OAuthUser", inversedBy="accessTokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * @var int
     */
    protected $userId;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $expires;

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
     * @return the $accessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param field_type $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
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
     * @param field_type $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
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
        return $this;
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
        $this->created = $created;
        return $this;
    }
}
