<?php
namespace ApiOauth2ServerTests\Model\Entity;

use ApiOauth2ServerTests\Bootstrap;

use ApiOauth2Server\Model\Entity\OAuthAccessToken;

/**
 * OAuthAccessToken test case.
 */
class OAuthAccessTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OAuthAccessToken
     */
    private $OAuthAccessToken;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->OAuthAccessToken = new OAuthAccessToken(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->OAuthAccessToken = null;
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
     * Tests OAuthAccessToken->getAccessToken()
     */
    public function testGetAccessToken()
    {
        $r = $this->OAuthAccessToken->getAccessToken(/* parameters */);
        $this->assertNull($r);
    }

    /**
     * Tests OAuthAccessToken->setAccessToken()
     */
    public function testSetAccessToken()
    {
        $r = $this->OAuthAccessToken->setAccessToken('abc123');
        $this->assertSame($this->OAuthAccessToken, $r);
    }

    /**
     * Tests OAuthAccessToken->getClientId()
     */
    public function testGetClientId()
    {
        $r = $this->OAuthAccessToken->getClientId(/* parameters */);
        $this->assertNull($r);
    }

    /**
     * Tests OAuthAccessToken->setClientId()
     */
    public function testSetClientId()
    {
        $r = $this->OAuthAccessToken->setClientId('119');
        $this->assertSame($this->OAuthAccessToken, $r);
    }

    /**
     * Tests OAuthAccessToken->getUserId()
     */
    public function testGetUserId()
    {
        $r = $this->OAuthAccessToken->getUserId(/* parameters */);
        $this->assertNull($r);
    }

    /**
     * Tests OAuthAccessToken->setUserId()
     */
    public function testSetUserId()
    {
        $r = $this->OAuthAccessToken->setUserId('101');
        $this->assertSame($this->OAuthAccessToken, $r);
    }
    /**
     * Tests OAuthAccessToken->getExpires()
     */
    public function testGetExpires()
    {
        $r = $this->OAuthAccessToken->getExpires(/* parameters */);
        $this->assertNull($r);

    }
    /**
     * Tests OAuthAccessToken->setExpires()
     */
    public function testSetExpires()
    {
        $d = new \DateTime();
        $d->setTimestamp(time());
        $r = $this->OAuthAccessToken->setExpires($d);
        $this->assertSame($this->OAuthAccessToken, $r);
    }

    /**
     * Tests OAuthAccessToken->getScope()
     */
    public function testGetScope()
    {
        $r = $this->OAuthAccessToken->getScope(/* parameters */);
        $this->assertNull($r);
    }

    /**
     * Tests OAuthAccessToken->setScope()
     */
    public function testSetScope()
    {
        $r = $this->OAuthAccessToken->setScope('get post');
        $this->assertSame($this->OAuthAccessToken, $r);
    }

    /**
     * Tests OAuthAccessToken->getCreated()
     */
    public function testGetCreated()
    {
        $r = $this->OAuthAccessToken->getCreated(/* parameters */);
        $this->assertNull($r);
    }

    /**
     * Tests OAuthAccessToken->setCreated()
     */
    public function testSetCreated()
    {
        $d = new \DateTime();
        $d->setTimestamp(time());

        $r = $this->OAuthAccessToken->setCreated($d);
        $this->assertSame($this->OAuthAccessToken, $r);
    }
}

