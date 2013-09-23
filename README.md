nmap
====

**nmap** is a PHP wrapper for [Nmap](http://nmap.org/), a free security scanner
for network exploration.


Usage
-----

```php
$hosts = Nmap::create()->scan([ 'williamdurand.fr' ]);

$ports = $hosts->getOpenPorts();

// ...
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
