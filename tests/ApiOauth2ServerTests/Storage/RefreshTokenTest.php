<?php
require_once 'module/ApiOauth2Server/src/ApiOauth2Server/Storage/RefreshToken.php';
require_once 'PHPUnit/Framework/TestCase.php';
/**
 * RefreshToken test case.
 */
class RefreshTokenTest extends PHPUnit_Framework_TestCase
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
	 */
    public function testGetRefreshToken()
    {
        $this->RefreshToken->getRefreshToken(/* parameters */);
    }
    /**
	 * Tests RefreshToken->setRefreshToken()
	 */
    public function testSetRefreshToken()
    {
        $this->markTestIncomplete("setRefreshToken test not implemented");
        $this->RefreshToken->setRefreshToken(/* parameters */);
    }
    /**
	 * Tests RefreshToken->unsetRefreshToken()
	 */
    public function testUnsetRefreshToken()
    {
        $this->markTestIncomplete("unsetRefreshToken test not implemented");
        $this->RefreshToken->unsetRefreshToken(/* parameters */);
    }
}

