<?php
namespace ApiOauth2ServerTests\ResponseType;

use ApiOauth2ServerTests\Bootstrap;
use ApiOauth2ServerTests\Assets;

use ApiOauth2Server\ResponseType\AccessToken;

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
        $accessTokenStorage  = new Assets\AccessTokenStorage();
        $refreshTokenStorage = new Assets\RefreshTokenStorage();
        $this->AccessToken = new AccessToken($accessTokenStorage, $refreshTokenStorage);

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
	 * Tests AccessToken->createAccessToken()
	 */
    public function testCreateAccessTokenUsesExistingAccessTokenWithScopeSorting()
    {
        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);

        /** Mock For OAuthAccessTokenRepository **/
        $returnAccessTokenVal1 = new \ApiOauth2Server\Model\Entity\OAuthAccessToken();
        $returnAccessTokenVal1->setScope('get post put')
            ->setAccessToken('accesstoken-1')
            ->setExpires(date_create()->setTimestamp(time() + 3600));

        $accessTokenMock = $this->getMockBuilder('ApiOauth2Server\Model\Repository\OAuthAccessTokenRepository')
            ->disableOriginalConstructor()
            ->setMethods(array('getUnexpiredAccessTokenByClientIdUserId', 'getResult'))
            ->getMock();
        $accessTokenMock->expects($this->once())
            ->method('getUnexpiredAccessTokenByClientIdUserId')
            ->with($this->isType('string'), $this->isType('int'))
            ->will($this->returnSelf());
        $accessTokenMock->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(array(
                $returnAccessTokenVal1,
            )));

        /** Mock For OAuthRefreshTokenRepository **/
        $returnRefreshTokenVal1 = new \ApiOauth2Server\Model\Entity\OAuthRefreshToken();
        $returnRefreshTokenVal1->setExpires(date_create()->setTimestamp(time() + 7200))->setRefreshToken('refreshtoken-1');

        $refreshTokenMock = $this->getMockBuilder('ApiOauth2Server\Model\Repository\OAuthRefreshTokenRepository')
            ->disableOriginalConstructor()
            ->setMethods(array('getUnusedRefreshTokenByClientIdAndUserIdAndScope', 'getOneOrNullResult'))
            ->getMock();
        $refreshTokenMock->expects($this->once())
            ->method('getUnusedRefreshTokenByClientIdAndUserIdAndScope')
            ->with($this->isType('string'), $this->isType('int'), $this->isType('string'))
            ->will($this->returnSelf());
        $refreshTokenMock->expects($this->once())
            ->method('getOneOrNullResult')
            ->will($this->returnValue($returnRefreshTokenVal1));

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmMock->expects($this->exactly(2))
            ->method('getRepository')
            ->with($this->isType('string'))
            ->will($this->onConsecutiveCalls($accessTokenMock, $refreshTokenMock));

        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);

        // inject the sm
        $this->AccessToken->setServiceManager($sm);

        $r = $this->AccessToken->createAccessToken('xx1xx2xx', 1, 'post get put', true);
        $this->assertSame(array(
            'access_token'       => "accesstoken-1",
            'token_type'         => "bearer",
            'scope'              => "get post put",
            'access_expires_in'  => 3600,
            'refresh_token'      => "refreshtoken-1",
            'refresh_expires_in' => 7200,
        ), $r);
    }

    /**
     * @group test2
     */
    public function testCreateAccessTokenAndGenerateNewAccessTokenAndRefreshToken()
    {
        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);

        /** Mock For OAuthAccessTokenRepository **/
        $returnAccessTokenVal1 = new \ApiOauth2Server\Model\Entity\OAuthAccessToken();
        $returnAccessTokenVal1->setScope('get')
            ->setAccessToken('accesstoken-1')
            ->setExpires(date_create()->setTimestamp(time() + 3600));

        $accessTokenMock = $this->getMockBuilder('ApiOauth2Server\Model\Repository\OAuthAccessTokenRepository')
            ->disableOriginalConstructor()
            ->setMethods(array('getUnexpiredAccessTokenByClientIdUserId', 'getResult'))
            ->getMock();
        $accessTokenMock->expects($this->once())
            ->method('getUnexpiredAccessTokenByClientIdUserId')
            ->with($this->isType('string'), $this->isType('int'))
            ->will($this->returnSelf());
        $accessTokenMock->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(array(
                $returnAccessTokenVal1,
            )));

        /** Mock For OAuthRefreshTokenRepository **/
        $returnRefreshTokenVal1 = null;

        $refreshTokenMock = $this->getMockBuilder('ApiOauth2Server\Model\Repository\OAuthRefreshTokenRepository')
            ->disableOriginalConstructor()
            ->setMethods(array('getUnusedRefreshTokenByClientIdAndUserIdAndScope', 'getOneOrNullResult'))
            ->getMock();
        $refreshTokenMock->expects($this->once())
            ->method('getUnusedRefreshTokenByClientIdAndUserIdAndScope')
            ->with($this->isType('string'), $this->isType('int'), $this->isType('string'))
            ->will($this->returnSelf());
        $refreshTokenMock->expects($this->once())
            ->method('getOneOrNullResult')
            ->will($this->returnValue($returnRefreshTokenVal1));

        /** Mock For Doctrine Entity Manager **/
        $doctrineOrmMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getRepository'))
            ->getMock();
        $doctrineOrmMock->expects($this->exactly(2))
            ->method('getRepository')
            ->with($this->isType('string'))
            ->will($this->onConsecutiveCalls($accessTokenMock, $refreshTokenMock));

        $sm->setService('doctrine.entitymanager.orm_default', $doctrineOrmMock);

        // inject the sm
        $this->AccessToken->setServiceManager($sm);

        // the scope is mimicking an already verified scope access for the user
        $r = $this->AccessToken->createAccessToken('xx1xx2xx', 1, 'post get put delete', true);

        $this->assertNotEmpty($r['access_token']);
        $this->assertNotEmpty($r['refresh_token']);

        // remove the access/refresh token because its a new hash that cannot be matched
        unset($r['access_token']);
        unset($r['refresh_token']);

        $this->assertSame(array(
            'token_type'         => "bearer",
            'scope'              => "delete get post put",
            'access_expires_in'  => 3600,
            'refresh_expires_in' => 1209600,
        ), $r);
    }

    /**
	 * Tests AccessToken->setServiceManager()
	 */
    public function testSetServiceManager()
    {
        $sm = Bootstrap::getServiceManager()->setAllowOverride(true);
        $r = $this->AccessToken->setServiceManager($sm);
        $this->assertSame($this->AccessToken, $r);
    }
}

