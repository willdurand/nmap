<?php

namespace Nmap;


class XmlOutputParser
{

    /**
     * @param $xmlFile
     * @return Host[]
     */
    public static function parseOutputFile($xmlFile)
    {
        $xml = simplexml_load_file($xmlFile);

        $hosts = array();
        foreach ($xml->host as $host) {
            $hosts[] = new Host(
                self::parseAddresses($host),
                (string)$host->status->attributes()->state,
                isset($host->hostnames) ? self::parseHostnames($host->hostnames->hostname) : array(),
                isset($host->ports) ? self::parsePorts($host->ports->port) : array()
            );
        }

        return $hosts;
    }

    /**
     * @param \SimpleXMLElement $xmlHostnames
     * @return Hostname[]
     */
    public static function parseHostnames(\SimpleXMLElement $xmlHostnames)
    {
        $hostnames = array();
        foreach ($xmlHostnames as $hostname) {
            $hostnames[] = new Hostname(
                (string)$hostname->attributes()->name,
                (string)$hostname->attributes()->type
            );
        }

        return $hostnames;
    }

    /**
     * @param \SimpleXMLElement $xmlPorts
     * @return Port[]
     */
    public static function parsePorts(\SimpleXMLElement $xmlPorts)
    {
        /**
         *
         */
        $ports = array();
        foreach ($xmlPorts as $port) {

            $name = $product = $version = null;

            if ($port->service) {
                $name = (string)$port->service->attributes()->name;
                $product = (string)$port->service->attributes()->product;
                $version = $port->service->attributes()->version;
            }

            $service = new Service(
                $name, $product, $version);

            $ports[] = new Port(
                (string)$port->attributes()->portid,
                (string)$port->attributes()->protocol,
                (string)$port->state->attributes()->state,
                $service);


        }

        return $ports;
    }

    /**
     * @param \SimpleXMLElement $host
     * @return Address[]
     */
    public static function parseAddresses(\SimpleXMLElement $host)
    {
        $addresses = array();
        foreach ($host->xpath('./address') as $address) {
            $attributes = $address->attributes();
            if (is_null($attributes)) {
                continue;
            }
            $addresses[(string)$attributes->addr] = new Address(
                (string)$attributes->addr,
                (string)$attributes->addrtype,
                isset($attributes->vendor) ? (string)$attributes->vendor : ''
            );
        }

        return $addresses;
    }
}