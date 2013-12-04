<?php
namespace ApiOauth2ServerTests\Storage;

use ApiOauth2ServerTests\Bootstrap;

use ApiOauth2Server\Model\Entity;
use ApiOauth2Server\Storage\UserCredentials;

/**
 * UserCredentials test case.
 */
class UserCredentialsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserCredentials
     */
    private $UserCredentials;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->UserCredentials = new UserCredentials(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->UserCredentials = null;
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
    }

    /**
     * Tests UserCredentials->checkUserCredentials()
     * @group test1
     */
    public function testCheckUserCredentials()
    {
        /** Request object mock **/
        $requestMock = $this->getMockBuilder('OAuth2\Request')
            ->disableOriginalConstructor()
            ->setMethods(array('request'))
            ->getMock();
        $requestMock->expects($this->once())
            ->method('request')
            ->with($this->equalTo('client_id'))
            ->will($this->returnValue(1));

        /** User Mock **/
        $userMock = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthUser')
            ->setMethods(array('getUserByUsernameAndPasswordAndClientId', 'getOneOrNullResult'))
            ->getMock();
        $userMock->expects($this->once())
            ->method('getUserByUsernameAndPasswordAndClientId')
            ->with($this->isType('string'), $this->isType('string'), $this->isType('string'))
            ->will($this->returnCallback(function ($username, $password, $clientId) use ($userMock) {
                return $userMock
                    ->setUserName($username)
                    ->setPassword($password)
                    ->setClients(new Entity\OAuthClient());
            }));
        $userMock->expects($this->exactly(1))
            ->method('getOneOrNullResult')
            ->will($this->returnSelf());

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmMock->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('ApiOauth2Server\Model\Entity\OAuthUser'))
            ->will($this->returnValue($userMock));

        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);
        $sm->setService('oauth2provider.server.main.request', $requestMock);

        $this->UserCredentials->setServiceLocator($sm);

        $r = $this->UserCredentials->checkUserCredentials('admin', 'password');
        $this->assertTrue($r);
    }

    /**
     * Tests UserCredentials->checkUserCredentials()
     * @group test2
     */
    public function testCheckUserCredentialsReturnsFalse()
    {
        /** Request stub **/
        $requestStub = $this->getMockBuilder('OAuth2\Request')
            ->disableOriginalConstructor()
            ->setMethods(array('request'))
            ->getMock();
        $requestStub->expects($this->once())
            ->method('request')
            ->will($this->returnValue(1));

        /** User Stub **/
        $userStub = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthUser')
            ->setMethods(array('getUserByUsernameAndPasswordAndClientId', 'getOneOrNullResult'))
            ->getMock();
        $userStub->expects($this->once())
            ->method('getUserByUsernameAndPasswordAndClientId')
            ->will($this->returnSelf());
        $userStub->expects($this->exactly(1))
            ->method('getOneOrNullResult')
            ->will($this->returnValue(false));

        /** Stub For Doctrine Entity Manager **/
        $doctrineOrmStub = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmStub->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($userStub));

        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmStub);
        $sm->setService('oauth2provider.server.main.request', $requestStub);

        $this->UserCredentials->setServiceLocator($sm);

        $r = $this->UserCredentials->checkUserCredentials('admin', 'password');
        $this->assertFalse($r);
    }

    /**
     * Tests UserCredentials->getUserDetails()
     * @group test3
     */
    public function testGetUserDetails()
    {
        /** Request object mock **/
        $requestMock = $this->getMockBuilder('OAuth2\Request')
            ->disableOriginalConstructor()
            ->setMethods(array('request'))
            ->getMock();
        $requestMock->expects($this->once())
            ->method('request')
            ->with($this->equalTo('client_id'))
            ->will($this->returnValue(1));

        /** User Mock **/
        $userMock = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthUser')
            ->setMethods(array('getUserWithScopeAndClientByUsernameAndClientId', 'getOneOrNullResult'))
            ->getMock();
        $userMock->expects($this->once())
            ->method('getUserWithScopeAndClientByUsernameAndClientId')
            ->with($this->isType('string'), $this->isType('string'))
            ->will($this->returnCallback(function ($username, $clientId) use ($userMock) {
                return $userMock
                    ->setUserName($username)
                    ->setPassword($clientId)
                    ->setClients(new Entity\OAuthClient());
            }));
        $userMock->expects($this->exactly(1))
            ->method('getOneOrNullResult')
            ->will($this->returnValue(array(
                'userId' => 1,
                'scope'  => 'get post put',
            )));

        /** Stub For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmMock->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('ApiOauth2Server\Model\Entity\OAuthUser'))
            ->will($this->returnValue($userMock));


        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);
        $sm->setService('oauth2provider.server.main.request', $requestMock);

        $this->UserCredentials->setServiceLocator($sm);

        $r = $this->UserCredentials->getUserDetails('admin', 1);
        $this->assertSame(array(
            'user_id' => 1,
            'scope' => 'get post put'
        ), $r);
    }
}

