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
    public function createAccessToken($client_id, $user_id, $scope = null, $includeRefreshToken = true)
    {
        $em = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        $existingAccessToken = $em
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthAccessToken')
            ->getUnexpiredAccessTokenByClientIdUserId($client_id, $user_id)
            ->getResult();

        $generateNewToken = true;
        if (!empty($existingAccessToken)) {
            foreach ($existingAccessToken as $val) {
                $scope1 = array_unique(explode(' ', $val->getScope()));
                $scope2 = array_unique(explode(' ', $scope));

                if (count($scope2) > count($scope1)) {
                    $scope1Copy = $scope1;
                    $scope1 = $scope2;
                    $scope2 = $scope1Copy;
                }

                if (0 === count(array_diff($scope1, $scope2))) {
                    $accessToken = $val->getAccessToken();
                    $generateNewToken = false;
                    break;
                }
            }
            unset($scope1, $scope2, $scope1Copy);
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

        if ($generateNewToken === false) {
            $token['access_token'] = $accessToken;
            $token['access_expires_in'] = Utility::calculateExpirationCountdown($val->getExpires()->getTimestamp());
        } else {
            $accessLifetime = $this->config['access_lifetime'] ? Utility::createTime($this->config['access_lifetime']) : null;
            $token['access_token'] = $this->generateAccessToken();
            $token['access_expires_in'] = Utility::calculateExpirationCountdown($accessLifetime);

            // save to db
            $this->tokenStorage->setAccessToken(
                $token["access_token"],
                $client_id,
                $user_id,
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
            ->getUnusedRefreshTokenByClientIdAndUserIdAndScope($client_id, $user_id, $token['scope'])
            ->getOneOrNullResult();

        // do not create new refresh token if current one has not expired
        if (isset($refreshToken) && $includeRefreshToken) {
            $includeRefreshToken    = false;
            $token["refresh_token"] = $refreshToken->getRefreshToken();
            $token["refresh_expires_in"] = Utility::calculateExpirationCountdown($refreshToken->getExpires());
        }

        if ($includeRefreshToken && $this->refreshStorage) {
            $refreshLifetime = Utility::createTime($this->config['refresh_token_lifetime']);
            $token["refresh_token"] = $this->generateRefreshToken();
            $token["refresh_expires_in"] = Utility::calculateExpirationCountdown($refreshLifetime);

            // save to db
            $this->refreshStorage->setRefreshToken(
                $token['refresh_token'],
                $client_id,
                $user_id,
                $refreshLifetime,
                $token["scope"]
            );
        }

        return $token;
    }

    public function setServiceManager(ServiceManager\ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
}
