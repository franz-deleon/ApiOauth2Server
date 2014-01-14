<?php
namespace ApiOauth2ServerTests\Storage;

use ApiOauth2ServerTests\Bootstrap;

use ApiOauth2Server\Storage\ClientCredentials;

/**
 * ClientCredentials2 test case.
 */
class ClientCredentialsTest extends \PHPUnit_Framework_TestCase
{
    /**
	 * @var ClientCredentials2
	 */
    private $ClientCredentials;

    /**
	 * Prepares the environment before running a test.
	 */
    protected function setUp()
    {
        parent::setUp();
        $this->ClientCredentials = new ClientCredentials(/* parameters */);
    }

    /**
	 * Cleans up the environment after running a test.
	 */
    protected function tearDown()
    {
        $this->ClientCredentials = null;
        parent::tearDown();
    }

    /**
	 * Constructs the test case.
	 */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * @group test1
     */
    public function testGetClientDetails()
    {
        $data = array('');

        /** stub for oauth client **/
        $oauthClientStub = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthClient')
            ->disableOriginalConstructor()
            ->setMethods(array('getClientDetails', 'getOneOrNullResult'))
            ->getMock();
        $oauthClientStub->expects($this->any())
            ->method('getClientDetails')
            ->will($this->returnSelf());
        $oauthClientStub->expects($this->any())
            ->method('getOneOrNullResult')
            ->will($this->returnValue(array(
                'clientId' => 1,
                'clientSecret' => 'loc',
                'redirectUri' => '/uri-not-needed',
                'grantTypes' => 'user_credentials',
            )));

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmMock->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('ApiOauth2Server\Model\Entity\OAuthClient'))
            ->will($this->returnValue($oauthClientStub));

        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);

        $this->ClientCredentials->setServiceLocator($sm);

        $r = $this->ClientCredentials->getClientDetails(1);
        $this->assertEquals(array(
            'client_id' => 1,
            'client_secret' => 'loc',
            'redirect_uri' => '/uri-not-needed',
            'grant_types' => 'user_credentials',
        ), $r);
    }

    /**
     * @group test2
     */
    public function testGetClientDetailsReturnsFalse()
    {
        $data = array('');

        /** stub for oauth client **/
        $oauthClientStub = $this->getMockBuilder('ApiOauth2Server\Model\Entity\OAuthClient')
            ->disableOriginalConstructor()
            ->setMethods(array('getClientDetails', 'getOneOrNullResult'))
            ->getMock();
        $oauthClientStub->expects($this->any())
            ->method('getClientDetails')
            ->will($this->returnSelf());
        $oauthClientStub->expects($this->any())
            ->method('getOneOrNullResult')
            ->will($this->returnValue(array()));

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmMock->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('ApiOauth2Server\Model\Entity\OAuthClient'))
            ->will($this->returnValue($oauthClientStub));

        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);

        $this->ClientCredentials->setServiceLocator($sm);

        $r = $this->ClientCredentials->getClientDetails(1);
        $this->assertFalse($r);
    }

    /**
     * @group test3
     */
    public function testCheckClientCredentials()
    {
        $clientCredentialsMock = $this->getMockBuilder(get_class($this->ClientCredentials))
            ->disableOriginalConstructor()
            ->setMethods(array('getClientDetails'))
            ->getMock();
        $clientCredentialsMock->expects($this->once())
            ->method('getClientDetails')
            ->with($this->equalTo('001'))
            ->will($this->returnValue(array(
                'client_secret' => 'XoXo',
            )));

        $r = $clientCredentialsMock->CheckClientCredentials('001', 'XoXo');
        $this->assertTrue($r);
    }

    /**
     * @group test4
     */
    public function testCheckClientCredentialsReturnsFalse()
    {
        $clientCredentialsMock = $this->getMockBuilder(get_class($this->ClientCredentials))
            ->disableOriginalConstructor()
            ->setMethods(array('getClientDetails'))
            ->getMock();
        $clientCredentialsMock->expects($this->once())
            ->method('getClientDetails')
            ->with($this->equalTo('001'))
            ->will($this->returnValue(array()));

        $r = $clientCredentialsMock->CheckClientCredentials('001', 'XoXo');
        $this->assertFalse($r);
    }

    /**
     * @group test5
     */
    public function testCheckRestrictedGrantType()
    {
        $r = $this->ClientCredentials->checkRestrictedGrantType('101', 'user_credentials');
        $this->assertTrue($r);
    }

    /**
     * @group test6
     */
    public function testIsPublicClient()
    {
        $r = $this->ClientCredentials->isPublicClient('101');
        $this->assertTrue($r);
    }
}

