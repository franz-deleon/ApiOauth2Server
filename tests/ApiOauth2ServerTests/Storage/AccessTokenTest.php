<?php
namespace ApiOauth2ServerTests\Storage;

use ApiOauth2ServerTests\Bootstrap;

use ApiOauth2Server\Model\Entity;
use ApiOauth2Server\Storage\AccessToken;

/**
 * AccessToken test case.
 */
class AccessTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
	 * @var AccessToken
	 */
    private $AccessToken;

    /**
	 * Prepares the environment before running a test.
	 */
    protected function setUp()
    {
        parent::setUp();
        $this->AccessToken = new AccessToken(/* parameters */);
    }

    /**
	 * Cleans up the environment after running a test.
	 */
    protected function tearDown()
    {
        $this->AccessToken = null;
        parent::tearDown();
    }

    /**
	 * Constructs the test case.
	 */
    public function __construct()
    {
    }

    /**
	 * Tests AccessToken->getAccessToken()
	 */
    public function testGetAccessToken()
    {
        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);

        /** Mock For OAuthAccessTokenRepository **/
        $time = time() + 3600;
        $returnAccessTokenVal1 = new \ApiOauth2Server\Model\Entity\OAuthAccessToken();
        $returnAccessTokenVal1
            ->setUserId('franzuserid')
            ->setClientId(1)
            ->setScope('get post put')
            ->setExpires(date_create()->setTimestamp($time));

        $accessTokenRepoMock = $this->getMockBuilder('ApiOauth2Server\Model\Repository\OAuthAccessTokenRepository')
            ->disableOriginalConstructor()
            ->setMethods(array('getAccessTokenById', 'getOneOrNullResult'))
            ->getMock();
        $accessTokenRepoMock->expects($this->once())
            ->method('getAccessTokenById')
            ->with($this->isType('string'))
            ->will($this->returnSelf());
        $accessTokenRepoMock->expects($this->once())
            ->method('getOneOrNullResult')
            ->will($this->returnValue($returnAccessTokenVal1));

        /** stub the hydrator **/
        $doctrineHydratorStub = $this->getMockBuilder('DoctrineModule\Stdlib\Hydrator\DoctrineObject')
            ->disableOriginalConstructor()
            ->setMethods(array('extract'))
            ->getMock();
        $doctrineHydratorStub->expects($this->once())
            ->method('extract')
            ->with($this->isType('object'))
            ->will($this->returnCallback(function ($val) {
                $return = array();
                $client = new Entity\OAuthClient();
                $client->setClientId($val->getClientId());

                $return['clientId'] = $client;
                $return['expires']  = $val->getExpires();
                $return['scope']    = $val->getScope();
                $return['userId']   = $val->getUserId();
                return $return;
            }));

        /** Stub for hydrator manager **/
        $hydratorManager = $sm->get('HydratorManager')->setAllowOverride(true);
        $hydratorManager->setService('DoctrineModule\Stdlib\Hydrator\DoctrineObject', $doctrineHydratorStub);

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmMock->expects($this->exactly(1))
            ->method('getRepository')
            ->with($this->equalTo('ApiOauth2Server\Model\Entity\OAuthAccessToken'))
            ->will($this->returnValue($accessTokenRepoMock));

        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);
        $this->AccessToken->setServiceLocator($sm);

        $r = $this->AccessToken->getAccessToken('XmyTokenX');
        $this->assertSame(array(
            'client_id' => 1,
            'expires' => $time,
            'scope' => 'get post put',
        ), $r);
    }

    /**
	 * Tests AccessToken->setAccessToken()
	 */
    public function testSetAccessToken()
    {
        $this->markTestIncomplete("setAccessToken test not implemented");
        $this->AccessToken->setAccessToken(/* parameters */);
    }
}

