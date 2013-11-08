nmap
====

**nmap** is a PHP wrapper for [Nmap](http://nmap.org/), a free security scanner
for network exploration.

[![Build
Status](https://travis-ci.org/willdurand/nmap.png?branch=master)](https://travis-ci.org/willdurand/nmap)


Usage
-----

```php
$hosts = Nmap::create()->scan([ 'williamdurand.fr' ]);

$ports = $hosts->getOpenPorts();
```

You can specify the ports you want to scan:

``` php
$nmap = new Nmap();

$nmap->scan([ 'williamdurand.fr' ], [ 21, 22, 80 ]);
```

**OS detection** and **Service Info** are disabled by default, if you want to
enable them, use the `enableOsDetection()` and/or `enableServiceInfo()` methods:

``` php
$nmap
    ->enableOsDetection()
    ->scan([ 'williamdurand.fr' ]);

$nmap
    ->enableServiceInfo()
    ->scan([ 'williamdurand.fr' ]);

// Fluent interface!
$nmap
    ->enableOsDetection()
    ->enableServiceInfo()
    ->scan([ 'williamdurand.fr' ]);
```

Turn the **verbose mode** by using the `enableVerbose()` method:

``` php
$nmap
    ->enableVerbose()
    ->scan([ 'williamdurand.fr' ]);
```

For some reasons, you might want to disable port scan, that is why **nmap**
provides a `disablePortScan()` method:

``` php
$nmap
    ->disablePortScan()
    ->scan([ 'williamdurand.fr' ]);
```

You can also disable the reverse DNS resolution with `disableReverseDNS()`:

``` php
$nmap
    ->disableReverseDNS()
    ->scan([ 'williamdurand.fr' ]);
```

Installation
------------

The recommended way to install nmap is through
[Composer](http://getcomposer.org/):

``` json
{
    "require": {
        "willdurand/nmap": "@stable"
    }
}
```


**Protip:** you should browse the
[`willdurand/nmap`](https://packagist.org/packages/willdurand/nmap)
page to choose a stable version to use, avoid the `@stable` meta constraint.


License
-------

nmap is released under the MIT License. See the bundled LICENSE file for
details.
