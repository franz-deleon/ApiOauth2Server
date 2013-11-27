<?php
namespace ApiOauth2Server\Storage;

use OAuth2\Storage\ClientCredentialsInterface;

class ClientCredentials extends AbstractStorage implements ClientCredentialsInterface
{
    /**
     * (non-PHPdoc)
     * @see \OAuth2\Storage\ClientInterface::getClientDetails()
     */
    public function getClientDetails($clientId)
    {
        $clientData = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')
            ->getRepository('ApiOauth2Server\Model\Entity\OAuthClient')
            ->getClientDetails($clientId)
            ->getResult();

        if (!empty($clientData)) {
            return $this->convertCamelKeysToUnderscore($clientData[0]);
        }

        return false;
    }

    /**
     * (non-PHPdoc)
     * @see \OAuth2\Storage\ClientCredentialsInterface::checkClientCredentials()
     */
    public function checkClientCredentials($clientId, $clientSecret = NULL)
    {
        $client = $this->getClientDetails($clientId);

        if ($client) {
            return $client['client_secret'] === $clientSecret;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see \OAuth2\Storage\ClientInterface::checkRestrictedGrantType()
     */
    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        // we do not support different grant types per client in this example
        return true;
    }
}
