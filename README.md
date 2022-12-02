![php 7.4](https://github.com/Enjoyzz/config/workflows/php%207.4/badge.svg)
![php 8.0](https://github.com/Enjoyzz/config/workflows/php%208.0/badge.svg)
[![Build Status](https://scrutinizer-ci.com/g/Enjoyzz/config/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/config/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Enjoyzz/config/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/config/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Enjoyzz/config/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/config/?branch=master)

# Install

```
composer require enjoys/config
```

# Usage

```php
$config = new Enjoys\Config\Config();
$config->addConfig($configFilepath1);
$config->addConfig($configFilepath2, [], \Enjoys\Config\Config::YAML);
$config->get('key', 'defaultValue'); //get from $array['key']
$config->get('key->subKey'); //get from $array['key']'subKey']
```
