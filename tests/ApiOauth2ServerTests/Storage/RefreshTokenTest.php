<?php
namespace ApiOauth2ServerTests\Storage;

use ApiOauth2ServerTests\Bootstrap;

use ApiOauth2Server\Model\Entity;
use ApiOauth2Server\Storage\RefreshToken;

/**
 * RefreshToken test case.
 */
class RefreshTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RefreshToken
     */
    private $RefreshToken;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->RefreshToken = new RefreshToken(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->RefreshToken = null;
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
    }

    /**
     * Tests RefreshToken->getRefreshToken()
     * @group test1
     */
    public function testGetRefreshToken()
    {
        $time = time() + 3600;

        /** User entitiy **/
        $userEntity = new Entity\OAuthUser();
        $userEntity->setUserId(1);

        /** Client entity **/
        $clientEntity = new Entity\OAuthClient();
        $clientEntity->setClientId(1);

        /** User Mock **/
        $refreshTokenMock = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthRefreshToken')
            ->setMethods(array('getRefreshTokenById', 'getOneOrNullResult'))
            ->getMock();
        $refreshTokenMock->expects($this->any())
            ->method('getRefreshTokenById')
            ->with($this->isType('string'))
            ->will($this->returnCallback(function ($token) use ($refreshTokenMock) {
                $refreshTokenMock->setRefreshToken($token);
                return $refreshTokenMock;
            }));
        $refreshTokenMock->expects($this->exactly(1))
            ->method('getOneOrNullResult')
            ->will($this->returnSelf());

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmMock->expects($this->exactly(1))
            ->method('getRepository')
            ->with($this->equalTo('ApiOauth2Server\Model\Entity\OAuthRefreshToken'))
            ->will($this->returnValue(
                $refreshTokenMock
                    ->setClientId($clientEntity)
                    ->setUserId($userEntity)
                    ->setUsed('no')
                    ->setScope('get post')
                    ->setExpires(date_create()->setTimestamp($time))
            ));

        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);

        $this->RefreshToken->setServiceLocator($sm);
        $r = $this->RefreshToken->getRefreshToken('xxRefreshTokenxx');

        $this->assertSame(array(
            'refresh_token' => 'xxRefreshTokenxx',
            'client_id' => 1,
            'user_id' => 1,
            'expires' => $time,
            'scope' => 'get post'
        ), $r);
    }

    /**
     * Tests RefreshToken->setRefreshToken()
     * @group test2
     */
    public function testSetRefreshToken()
    {
        $time = time() + 3600;

        /** client repo mock **/
        $clientMock = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthClient')
            ->setMethods(array('find'))
            ->getMock();
        $clientMock->expects($this->once())
            ->method('find')
            ->with($this->isType('int'))
            ->will($this->returnValue(new Entity\OAuthClient()));

        /** user repo mock **/
        $userMock = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthUser')
            ->setMethods(array('find'))
            ->getMock();
        $userMock->expects($this->once())
            ->method('find')
            ->with($this->isType('int'))
            ->will($this->returnValue(new Entity\OAuthUser()));

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository', 'persist', 'flush'))
            ->getMock();
        $doctrineOrmMock->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf('ApiOauth2Server\Model\Entity\OAuthRefreshToken'))
            ->will($this->returnSelf());
        $doctrineOrmMock->expects($this->once())
            ->method('flush')
            ->will($this->returnSelf());
        $doctrineOrmMock->expects($this->exactly(2))
            ->method('getRepository')
            ->with($this->logicalOr('ApiOauth2Server\Model\Entity\OAuthClient', 'ApiOauth2Server\Model\Entity\OAuthUser'))
            ->will($this->onConsecutiveCalls($clientMock, $userMock));

        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);

        $this->RefreshToken->setServiceLocator($sm);

        $r = $this->RefreshToken->setRefreshToken('xxRefreshTokenxx', 1, 1, date_create()->setTimestamp($time), 'get post');
        $this->assertNull($r);
    }
    /**
     * Tests RefreshToken->unsetRefreshToken()
     * @group test3
     */
    public function testUnsetRefreshToken()
    {
        /** refresh token repo mock **/
        $refreshMock = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthRefreshToken')
            ->setMethods(array('find', 'setUsed'))
            ->getMock();
        $refreshMock->expects($this->once())
            ->method('setUsed')
            ->with($this->equalTo('yes'))
            ->will($this->returnSelf());
        $refreshMock->expects($this->once())
            ->method('find')
            ->with($this->isType('string'))
            ->will($this->returnSelf());

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository', 'flush'))
            ->getMock();
        $doctrineOrmMock->expects($this->once())
            ->method('flush')
            ->will($this->returnSelf());
        $doctrineOrmMock->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('ApiOauth2Server\Model\Entity\OAuthRefreshToken'))
            ->will($this->returnValue($refreshMock));

        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);

        $this->RefreshToken->setServiceLocator($sm);

        $r = $this->RefreshToken->unsetRefreshToken('xxRefreshTokenxx');
        $this->assertNull($r);
    }
}

