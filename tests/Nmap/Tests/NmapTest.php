<?php

namespace Nmap\Tests;

use Nmap\Nmap;

class NmapTest extends TestCase
{
    public function testScan()
    {
        $hosts = Nmap::create()->scan([ 'williamdurand.fr' ]);
        $this->assertCount(1, $hosts);

        $host = current($hosts);
        $this->assertCount(4, $host->getPorts());
        $this->assertCount(2, $host->getOpenPorts());
        $this->assertCount(2, $host->getClosedPorts());
    }
}
