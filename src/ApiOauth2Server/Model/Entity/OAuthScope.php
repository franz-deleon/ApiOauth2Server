<?php
namespace ApiOauth2Server\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ApiOauth2Server\Model\Repository\OAuthScopeRepository")
 * @ORM\Table(name="oauth_scopes")
 */
class OAuthScope
{
    /**
     * @ORM\Id
     * @ORM\Column(name="scope_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $scopeId;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    protected $type = 'supported';

    /**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    protected $scope;

    /**
     * @ORM\ManyToOne(targetEntity="ApiOauth2Server\Model\Entity\OAuthClient", inversedBy="scopes", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="client_id")
     * @var string
     */
    protected $clientId;

    /**
     * @ORM\ManyToOne(targetEntity="ApiOauth2Server\Model\Entity\OAuthUser", inversedBy="scopes", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * @var string
     */
    protected $userId;

	/**
     * @return the $scopeId
     */
    public function getScopeId()
    {
        return $this->scopeId;
    }

	/**
     * @param number $scopeId
     */
    public function setScopeId($scopeId)
    {
        $this->scopeId = $scopeId;
    }

	/**
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

	/**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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



}
