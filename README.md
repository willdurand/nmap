nmap
====

**nmap** is a PHP wrapper for [Nmap](http://nmap.org/), a free security scanner
for network exploration.

[![Build Status](https://travis-ci.org/DavidGoodwin/nmap.svg?branch=master)](https://travis-ci.org/DavidGoodwin/nmap)


Usage
-----

```php
$hosts = Nmap::create()->scan([ 'example.com' ]);

$ports = $hosts->getOpenPorts();
```

You can specify the ports you want to scan:

``` php
$nmap = new Nmap();

$nmap->scan([ 'example.com' ], [ 21, 22, 80 ]);
```

**OS detection** and **Service Info** are disabled by default, if you want to
enable them, use the `enableOsDetection()` and/or `enableServiceInfo()` methods:

``` php
$nmap
    ->enableOsDetection()
    ->scan([ 'example.com' ]);

$nmap
    ->enableServiceInfo()
    ->scan([ 'example.com' ]);

// Fluent interface!
$nmap
    ->enableOsDetection()
    ->enableServiceInfo()
    ->scan([ 'example.com' ]);
```

Turn the **verbose mode** by using the `enableVerbose()` method:

``` php
$nmap
    ->enableVerbose()
    ->scan([ 'example.com' ]);
```

For some reasons, you might want to disable port scan, that is why **nmap**
provides a `disablePortScan()` method:

``` php
$nmap
    ->disablePortScan()
    ->scan([ 'example.com' ]);
```

You can also disable the reverse DNS resolution with `disableReverseDNS()`:

``` php
$nmap
    ->disableReverseDNS()
    ->scan([ 'example.com' ]);
```

You can define the process timeout (default to 60 seconds) with `setTimeout()`:

``` php
$nmap
    ->setTimeout(120)
    ->scan([ 'example.com' ]);
```

Installation
------------

The recommended way to install nmap is through
[Composer](http://getcomposer.org/):

``` json
{
    "require": {
        "palepurple/nmap": "^1.0"
    }
}
```



License
-------

nmap is released under the MIT License. See the bundled LICENSE file for
details.
