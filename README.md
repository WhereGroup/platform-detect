Detect / distinguish operating system platform in PHP.

Unlinke a simple directory separator test, can tell the difference between Mac OS X, BSD and Linux, and
between Cygwin and bare Windows. 

Can also detect legacy 32 bit platforms. 

```
php > $platform = \WhereGroup\PlatformDetect\Platform::guess();
php > echo $platform->getOrder() . "\n";
NIX
php > echo $platform->getFamily() . "\n";
LINUX
php > echo $platform->getBitness() . "\n";
64
```
