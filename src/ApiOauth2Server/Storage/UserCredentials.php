<?php
namespace ApiOauth2Server\Storage;

use OAuth2\Storage\UserCredentialsInterface;

class UserCredentials extends AbstractStorage implements UserCredentialsInterface
{
    public function checkUserCredentials($username, $password)
    {
        $user = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')
            ->getUserByUsernameAndPassword($username, $password);

        if (empty($user)) {
            return false;
        }

        $clientId = (int) $this->getServiceLocator()->get('request')->getQuery('client_id');

        $user = array_pop($user);
        foreach ($user->getClientIds() as $client) {
            $userClientId = (int) $client->getClientId();
            if ($userClientId === $clientId) {
                return true;
            }
        }

        return false;
    }

    public function getUserDetails($username)
    {
        $clientId = (int) $this->getServiceLocator()->get('request')->getQuery('client_id');

        $user = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthUser')
            ->findOneByUserName($username);

        //var_dump($user->getClientIds());
    }
}
