<?php
namespace ApiOauth2Server\Storage;

use ApiOauth2Server\Stdlib\Utility;
use ApiOauth2Server\Model\Entity\OAuthRefreshToken as RToken;

use OAuth2\Storage\RefreshTokenInterface;

class RefreshToken extends AbstractStorage implements RefreshTokenInterface
{
    public function getRefreshToken($refreshToken)
    {
        $refreshToken = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthRefreshToken')
            ->getRefreshTokenById($refreshToken)
            ->getOneOrNullResult();

        if (isset($refreshToken)
            && $refreshToken->getUsed() === RToken::USED_NO
            && $refreshToken->getExpires()->getTimestamp() > Utility::createTime()->getTimestamp()
        ) {
            $return = array();
            $return['refresh_token'] = $refreshToken->getRefreshToken();
            $return['client_id'] = $refreshToken->getClientId()->getClientId();
            $return['user_id']   = $refreshToken->getUserId()->getUserId();
            $return['expires']   = $refreshToken->getExpires()->getTimestamp();
            $return['scope']     = $refreshToken->getScope();

            return $return;
        }
    }

    /**
     * @var $expires Expire need to be in DateTime
     * @see \OAuth2\Storage\RefreshTokenInterface::setRefreshToken()
     */
    public function setRefreshToken($refToken, $clientId, $userId, $expires, $scope = null)
    {
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $client = $em->getRepository('ApiOauth2Server\Model\Entity\OAuthClient')->find($clientId);
        $user   = $em->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')->find($userId);

        $refreshToken = $this->getServiceLocator()->get('ApiOauth2Server/Model/Entity/RefreshToken');
        $refreshToken->setRefreshToken($refToken)
            ->setClientId($client)
            ->setUserId($user)
            ->setExpires($expires)
            ->setUsed(RToken::USED_NO)
            ->setScope($scope);

        $em->persist($refreshToken);
        $em->flush();
    }

    public function unsetRefreshToken($refreshToken)
    {
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $refreshToken = $em->getRepository('ApiOauth2Server\Model\Entity\OAuthRefreshToken')
            ->find($refreshToken);

        $refreshToken->setUsed(RToken::USED_YES);
        $em->flush();
    }
}
