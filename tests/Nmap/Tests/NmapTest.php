<?php

namespace Nmap\Tests;

use Nmap\Host;
use Nmap\Nmap;
use Nmap\Port;

class NmapTest extends TestCase
{
    public function testScan()
    {
        $outputFile      = __DIR__ . '/Fixtures/test_scan.xml';
        $expectedCommand = sprintf("nmap -oX '%s' 'williamdurand.fr'", $outputFile);

        $executor = $this->getMock('Nmap\Util\ProcessExecutor');
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedCommand))
            ->will($this->returnValue(0));

        $nmap  = new Nmap($executor, $outputFile);
        $hosts = $nmap->scan(array('williamdurand.fr'));
        $this->assertCount(1, $hosts);

        $host = current($hosts);

        $this->assertEquals('204.232.175.78', $host->getAddress());
        $this->assertEquals(Host::STATE_UP, $host->getState());

        $hostnames = $host->getHostnames();
        $this->assertCount(2, $hostnames);
        $this->assertEquals('williamdurand.fr', $hostnames[0]->getName());
        $this->assertEquals('user', $hostnames[0]->getType());
        $this->assertEquals('pages.github.com', $hostnames[1]->getName());
        $this->assertEquals('PTR', $hostnames[1]->getType());

        $ports = $host->getPorts();
        $this->assertCount(5, $ports);
        $this->assertCount(3, $host->getOpenPorts());
        $this->assertCount(2, $host->getClosedPorts());

        $this->assertEquals(22, $ports[0]->getNumber());
        $this->assertEquals('tcp', $ports[0]->getProtocol());
        $this->assertEquals(Port::STATE_OPEN, $ports[0]->getState());
        $this->assertNotNull($ports[0]->getService());
        $this->assertEquals('ssh', $ports[0]->getService()->getName());
    }
}
