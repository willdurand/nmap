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

    public function testScanSpecifyingPorts()
    {
        $outputFile      = __DIR__ . '/Fixtures/test_scan_specifying_ports.xml';
        $expectedCommand = sprintf("nmap -p 21,22,80 -oX '%s' 'williamdurand.fr'", $outputFile);

        $executor = $this->getMock('Nmap\Util\ProcessExecutor');
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedCommand))
            ->will($this->returnValue(0));

        $nmap  = new Nmap($executor, $outputFile);
        $hosts = $nmap->scan(array('williamdurand.fr'), array(21,22,80));
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
        $this->assertCount(3, $ports);

        $this->assertEquals(21, $ports[0]->getNumber());
        $this->assertEquals('ftp', $ports[0]->getService()->getName());
        $this->assertEquals(22, $ports[1]->getNumber());
        $this->assertEquals('ssh', $ports[1]->getService()->getName());
        $this->assertEquals(80, $ports[2]->getNumber());
        $this->assertEquals('http', $ports[2]->getService()->getName());
    }

    public function testScanWithOsDetection()
    {
        $outputFile      = __DIR__ . '/Fixtures/test_scan_with_os_detection.xml';
        $expectedCommand = sprintf("nmap -O -oX '%s' 'williamdurand.fr'", $outputFile);

        $executor = $this->getMock('Nmap\Util\ProcessExecutor');
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedCommand))
            ->will($this->returnValue(0));

        $nmap  = new Nmap($executor, $outputFile);
        $hosts = $nmap
            ->enableOsDetection()
            ->scan(array('williamdurand.fr'));
    }

    public function testScanWithServiceInfo()
    {
        $outputFile      = __DIR__ . '/Fixtures/test_scan_with_service_info.xml';
        $expectedCommand = sprintf("nmap -sV -oX '%s' 'williamdurand.fr'", $outputFile);

        $executor = $this->getMock('Nmap\Util\ProcessExecutor');
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedCommand))
            ->will($this->returnValue(0));

        $nmap  = new Nmap($executor, $outputFile);
        $hosts = $nmap
            ->enableServiceInfo()
            ->scan(array('williamdurand.fr'));
    }

    public function testScanWithVerbose()
    {
        $outputFile      = __DIR__ . '/Fixtures/test_scan_with_verbose.xml';
        $expectedCommand = sprintf("nmap -v -oX '%s' 'williamdurand.fr'", $outputFile);

        $executor = $this->getMock('Nmap\Util\ProcessExecutor');
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedCommand))
            ->will($this->returnValue(0));

        $nmap  = new Nmap($executor, $outputFile);
        $hosts = $nmap
            ->enableVerbose()
            ->scan(array('williamdurand.fr'));
    }

    public function testPingScan()
    {
        $outputFile      = __DIR__ . '/Fixtures/test_ping_scan.xml';
        $expectedCommand = sprintf("nmap -sn -oX '%s' 'williamdurand.fr'", $outputFile);

        $executor = $this->getMock('Nmap\Util\ProcessExecutor');
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedCommand))
            ->will($this->returnValue(0));

        $nmap  = new Nmap($executor, $outputFile);
        $hosts = $nmap
            ->disablePortScan()
            ->scan(array('williamdurand.fr'));
    }

    public function testScanWithoutReverseDNS()
    {
        $outputFile      = __DIR__ . '/Fixtures/test_ping_without_reverse_dns.xml';
        $expectedCommand = sprintf("nmap -n -oX '%s' 'williamdurand.fr'", $outputFile);

        $executor = $this->getMock('Nmap\Util\ProcessExecutor');
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedCommand))
            ->will($this->returnValue(0));

        $nmap  = new Nmap($executor, $outputFile);
        $hosts = $nmap
            ->disableReverseDNS()
            ->scan(array('williamdurand.fr'));
    }

    public function testScanWithTreatHostsAsOnline() {
        $outputFile = __DIR__ . '/Fixtures/test_scan_with_verbose.xml';
        $expectedCommand = sprintf("nmap -Pn -oX '%s' 'williamdurand.fr'", $outputFile);

        $executor = $this->getMock('Nmap\Util\ProcessExecutor');
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedCommand))
            ->will($this->returnValue(0));

        $nmap = new Nmap($executor, $outputFile);
        $hosts = $nmap->treatHostsAsOnline()->scan(array('williamdurand.fr'));
    }

}
