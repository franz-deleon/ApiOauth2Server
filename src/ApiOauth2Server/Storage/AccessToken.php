<?php
namespace ApiOauth2Server\Storage;

use ApiOauth2Server\Lib\Utility;

use OAuth2\Storage\AccessTokenInterface;

class AccessToken extends AbstractStorage implements AccessTokenInterface
{
    /**
     * (non-PHPdoc)
     * @see \OAuth2\Storage\AccessTokenInterface::getAccessToken()
     */
    public function getAccessToken($oauthToken)
    {
        $accessToken = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthAccessToken')
            ->find($oauthToken);

        if (null !== $accessToken) {
            if ($accessToken->getExpires()->getTimestamp() > Utility::createTime()->getTimestamp()) {
                $hydrator = $this->getServiceLocator()
                    ->get('HydratorManager')
                    ->get('DoctrineModule\Stdlib\Hydrator\DoctrineObject');

                $accessToken = $hydrator->extract($accessToken);
                $return = array();
                foreach (array('clientId', 'expires', 'scope') as $key) {
                    if (isset($accessToken[$key])) {
                        $return[$key] = $accessToken[$key];
                    }
                }

                // just retrieve the client id
                if ($return['clientId']) {
                    $return['clientId'] = $return['clientId']->getClientId();
                }

                // convert the datetime to unix
                if ($return['expires'] instanceof \DateTime) {
                    $return['expires'] = $return['expires']->getTimestamp();
                }
                unset($accessToken);

                return $this->convertCamelKeysToUnderscore($return);
            }
        }
    }

    /**
     * (non-PHPdoc)
     * @see \OAuth2\Storage\AccessTokenInterface::setAccessToken()
     */
    public function setAccessToken($oauthToken, $clientId, $userId, $expires, $scope = null)
    {
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $client = $em->getRepository('ApiOauth2Server\Model\Entity\OAuthClient')->find($clientId);
        $user   = $em->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')->find($userId);

        $accessToken = $this->getServiceLocator()->get('ApiOauth2Server/Model/Entity/AccessToken');
        $accessToken->setAccessToken($oauthToken)
            ->setClientId($client)
            ->setUserId($user)
            ->setExpires($expires)
            ->setScope($scope);

        $em->persist($accessToken);
        $em->flush();
    }
}
