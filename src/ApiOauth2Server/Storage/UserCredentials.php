<?php
namespace ApiOauth2Server\Storage;

use OAuth2\Storage\UserCredentialsInterface;

class UserCredentials extends AbstractStorage implements UserCredentialsInterface
{
    public function checkUserCredentials($username, $password)
    {
        $user = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')
            ->getUserByUsernameAndPassword($username, $password)
            ->getResult();

        if (empty($user)) {
            return false;
        }

        $clientId = (string) $this->getServiceLocator()->get('request')->getPost('client_id');

        $user = array_pop($user);
        foreach ($user->getClients() as $client) {
            $userClientId = (string) $client->getClientId();
            if ($userClientId === $clientId) {
                return true;
            }
        }

        return false;
    }

    public function getUserDetails($username)
    {
        $clientId = (int) $this->getServiceLocator()->get('request')->getPost('client_id');

        $userDetails = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')
            ->getUserWithScopeAndClientByUsernameAndClientId($username, $clientId)
            ->getArrayResult();

        if (!empty($userDetails)) {
            // remove the first aggregate value
            unset($userDetails[0][0]);
            return $this->convertCamelKeysToUnderscore($userDetails[0]);
        }

        return false;
    }
}
