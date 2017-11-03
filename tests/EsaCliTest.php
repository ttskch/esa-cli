<?php
namespace Ttskch\EsaCli;

use PHPUnit\Framework\TestCase;

class EsaCliTest extends TestCase
{
    /**
     * @var EsaCli
     */
    protected $skeleton;

    protected function setUp()
    {
        parent::setUp();
        $this->skeleton = new EsaCli;
    }

    public function testNew()
    {
        $actual = $this->skeleton;
        $this->assertInstanceOf('\Ttskch\EsaCli\EsaCli', $actual);
    }
}
