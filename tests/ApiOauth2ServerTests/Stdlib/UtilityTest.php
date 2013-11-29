<?php
namespace ApiOauth2ServerTests\Stdlib;

use ApiOauth2ServerTests\Bootstrap;

use ApiOauth2Server\Stdlib\Utility;

/**
 * Utility test case.
 */
class UtilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Utility
     */
    private $Utility;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->Utility = new Utility(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->Utility = null;
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
    }

    /**
     * Tests Utility::createTime()
     */
    public function testCreateTime()
    {
        $now = time();
        $r = Utility::createTime(/* parameters */);
        $this->assertInstanceOf('DateTime', $r);
        $this->assertEquals($now, $r->getTimestamp());
    }

    /**
     * Tests Utility::createTime()
     */
    public function testCreateTimeWithOffset()
    {
        $now = time() + 100;
        $r = Utility::createTime(100);
        $this->assertInstanceOf('DateTime', $r);
        $this->assertEquals($now, $r->getTimestamp());
    }

    /**
     * Tests Utility::calculateTTD()
     */
    public function testCalculateTTD()
    {
        $ttd = time() + 3600; // add 1 hour before dying
        $r = Utility::calculateTTD($ttd);

        $this->assertEquals(3600, $r);
    }

    /**
     * Tests Utility::calculateTTD()
     */
    public function testCalculateTTDReturnAsString()
    {
        $ttd = time() + 3600; // add 1 hour before dying
        $r = Utility::calculateTTD($ttd, false);

        $this->assertEquals('60 minutes 0 seconds', $r);
    }

    /**
     * Tests Utility::calculateTTD()
     */
    public function testCalculateTTDWhereTimeIsInPast()
    {
        $ttd = time() - 3600; // less 1 hour
        $r = Utility::calculateTTD($ttd);

        $this->assertEquals(0, $r);
    }
}
