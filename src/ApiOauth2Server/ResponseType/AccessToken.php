<?php
namespace ApiOauth2Server\ResponseType;

use ApiOauth2Server\Stdlib\Utility;

use OAuth2\ResponseType\AccessToken as OAuth2AccessToken;

use Zend\ServiceManager;

class AccessToken extends OAuth2AccessToken implements ServiceManager\ServiceManagerAwareInterface
{
    protected $serviceManager;

    /**
     * Overrides the access token creation because we want to
     * 'recycle' access tokens and not create a new one everytime
     *
     * (non-PHPdoc)
     * @see \OAuth2\ResponseType\AccessToken::createAccessToken()
     */
    public function createAccessToken($clientId, $userId, $scope = null, $includeRefreshToken = true)
    {
        $em = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        $existingAccessToken = $em
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthAccessToken')
            ->getUnexpiredAccessTokenByClientIdUserId($clientId, $userId)
            ->getResult();

        $createNewAccessToken = true;
        if (!empty($existingAccessToken)) {
            foreach ($existingAccessToken as $val) {
                $scope1 = array_unique(explode(' ', $val->getScope()));
                $scope2 = array_unique(explode(' ', $scope));

                if (count($scope2) > count($scope1)) {
                    $scope1Copy = $scope1;
                    $scope1 = $scope2;
                    $scope2 = $scope1Copy;
                    unset($scope1Copy);
                }

                // an existing access token has been found
                if (0 === count(array_diff($scope1, $scope2))) {
                    $accessToken = $val->getAccessToken();
                    $createNewAccessToken = false;
                    break;
                }
            }
            unset($scope1, $scope2);
        }

        // sort scope
        $scope = explode(' ', $scope);
        sort($scope);
        $scope = implode(' ', $scope);

        $token = array(
            "access_token" => null,
            "token_type"   => $this->config['token_type'],
            "scope"        => $scope,
            "access_expires_in" => null,
        );

        if ($createNewAccessToken === false) {
            $token['access_token']      = $accessToken;
            $token['access_expires_in'] = Utility::calculateTTD($val->getExpires()->getTimestamp());
        } else {
            $accessLifetime = $this->config['access_lifetime'] ? Utility::createTime($this->config['access_lifetime']) : null;
            $token['access_token']      = $this->generateAccessToken();
            $token['access_expires_in'] = Utility::calculateTTD($accessLifetime);

            // save to db
            $this->tokenStorage->setAccessToken(
                $token["access_token"],
                $clientId,
                $userId,
                $accessLifetime,
                $token["scope"]
            );
        }

        /*
         * Issue a refresh token also, if we support them
         *
         * Refresh Tokens are considered supported if an instance of OAuth2_Storage_RefreshTokenInterface
         * is supplied in the constructor
         */
        $refreshToken = $em
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthRefreshToken')
            ->getUnusedRefreshTokenByClientIdAndUserIdAndScope($clientId, $userId, $token['scope'])
            ->getOneOrNullResult();

        // do not create new refresh token if current one has not expired
        if (isset($refreshToken) && $includeRefreshToken) {
            $includeRefreshToken    = false;
            $token["refresh_token"] = $refreshToken->getRefreshToken();
            $token["refresh_expires_in"] = Utility::calculateTTD($refreshToken->getExpires());
        }

        if ($includeRefreshToken && $this->refreshStorage) {
            $refreshLifetime = Utility::createTime($this->config['refresh_token_lifetime']);
            $token["refresh_token"] = $this->generateRefreshToken();
            $token["refresh_expires_in"] = Utility::calculateTTD($refreshLifetime);

            // save to db
            $this->refreshStorage->setRefreshToken(
                $token['refresh_token'],
                $clientId,
                $userId,
                $refreshLifetime,
                $token["scope"]
            );
        }

        return $token;
    }

    public function setServiceManager(ServiceManager\ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}
