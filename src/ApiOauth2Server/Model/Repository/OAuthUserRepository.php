<?php

namespace ApiOauth2Server\Model\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * OAuthUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OAuthUserRepository extends EntityRepository
{
    public function getUserByUsernameAndPassword($username, $password)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.userName = :username')
            ->andWhere('u.password = :password')
            ->setParameters(array(
                'username' => $username,
                'password' => $password,
            ));

        return $queryBuilder->getQuery()->getResult();
    }
}