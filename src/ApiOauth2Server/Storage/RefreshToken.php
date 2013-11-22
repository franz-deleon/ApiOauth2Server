<?php
namespace ApiOauth2Server\Storage;

use ApiOauth2Server\Model\Entity\OAuthRefreshToken as RToken;

use OAuth2\Storage\RefreshTokenInterface;

class RefreshToken extends AbstractStorage implements RefreshTokenInterface
{
    public function getRefreshToken($refreshToken)
    {
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $refreshToken = $em->getRepository('ApiOauth2Server\Model\Entity\OAuthRefreshToken')->find($refreshToken);

        if (null !== $refreshToken && $refreshToken->getUsed() === RToken::USED_NO) {
            $clientId = $refreshToken->getClientId()->getClientId();
            $userId   = $refreshToken->getUserId()->getUserId();

            $return = array();
            $return['refresh_token'] = $refreshToken->getRefreshToken();
            $return['client_id'] = $clientId;
            $return['user_id']   = $userId;
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
        $clientId = $em->getRepository('ApiOauth2Server\Model\Entity\OAuthClient')->find($clientId);
        $userId   = $em->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')->find($userId);

        $refreshToken = $this->getServiceLocator()->get('ApiOauth2Server/Model/Entity/RefreshToken');
        $refreshToken->setRefreshToken($refToken)
            ->setClientId($clientId)
            ->setUserId($userId)
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
