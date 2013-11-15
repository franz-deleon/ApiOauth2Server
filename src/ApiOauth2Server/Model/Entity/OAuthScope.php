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
     * @ORM\Column(type="string", length=100, nullable=false)
     * @var string
     */
    protected $type = 'supported';

    /**
     * @ORM\Column(type="string", length=200)
     * @var string
     */
    protected $scope;

    /**
     * @ORM\Column(type="string", length=200)
     * @ORM\ManyToOne(targetEntity="ApiOauth2Server\Model\Entity\OAuthClient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="clientId")
     * @var string
     */
    protected $clientId;
}
