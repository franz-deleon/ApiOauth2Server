<?php
namespace ApiOauth2Server\Controller;

use OAuth2Provider\Controller\UserCredentialsController as OAuth2ProviderController;

class UserCredentialsController extends OAuth2ProviderController
{
    public function resourceAction($scope = null)
    {
        if (null === $scope) {
            $server = $this->getServiceLocator()->get('oauth2provider.server.main');
            $scope  = $server->getRequest()->request('scope');
            if (empty($scope)) {
                $scope = $this->getServiceLocator()
                    ->get('oauth2provider.server.main.scope_type.scope')
                    ->getDefaultScope();
            }
        }

        return parent::resourceAction($scope);
    }
}
