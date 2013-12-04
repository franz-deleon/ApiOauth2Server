<?php
namespace ApiOauth2Server\Storage;

use OAuth2\Storage\UserCredentialsInterface;

class UserCredentials extends AbstractStorage implements UserCredentialsInterface
{
    /**
     * (non-PHPdoc)
     * @see \OAuth2\Storage\UserCredentialsInterface::checkUserCredentials()
     */
    public function checkUserCredentials($username, $password)
    {
        $clientId = (string) $this->getServiceLocator()->get('oauth2provider.server.main.request')->request('client_id');

        $user = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')
            ->getUserByUsernameAndPasswordAndClientId($username, $password, $clientId)
            ->getOneOrNullResult();

        if (!empty($user)) {
            return true;
        }

        return false;
    }

    /**
     * (non-PHPdoc)
     * @see \OAuth2\Storage\UserCredentialsInterface::getUserDetails()
     */
    public function getUserDetails($username)
    {
        $clientId = (string) $this->getServiceLocator()->get('oauth2provider.server.main.request')->request('client_id');

        $userDetails = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')
            ->getUserWithScopeAndClientByUsernameAndClientId($username, $clientId)
            ->getOneOrNullResult();

        if (!empty($userDetails)) {
            return $this->convertCamelKeysToUnderscore($userDetails);
        }

        return false;
    }
}
