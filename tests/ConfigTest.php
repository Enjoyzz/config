<?php

declare(strict_types=1);

namespace Tests\Enjoys\Config;

use Enjoys\Config\Config;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 * @package Tests\Enjoys\Config
 */
class ConfigTest extends TestCase
{

    protected function setUp(): void
    {
        $_ENV['TEST'] = 'XXX';

    }

    protected function tearDown(): void
    {
        unset($_ENV['TEST']);
    }

    public function test1()
    {
        $config = new Config();
        $logger = new LoggerSimple();
        $config->setLogger($logger);
        $config->addConfig('foo = bar');

        $this->assertSame(['foo' => 'bar'], $config->getConfig());
        $this->assertSame('bar', $config->getConfig('foo'));
        $this->assertSame(false, $config->getConfig('baz', false));
        $this->assertSame([], $logger->getError());
    }

    public function test2()
    {
        $this->expectException(Exception::class);
        $config = new Config(new LoggerSimple());
        $config->addConfig('foo = bar', [], 'InvalidClass');
    }

    public function test3()
    {
        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => 'foo = bar']);
        $this->assertSame(['test' => ['foo' => 'bar']], $config->getConfig());
        $config->addConfig(['test' => 'foo2 = bar']);
        $this->assertSame(['test' => ['foo' => 'bar', 'foo2' => 'bar']], $config->getConfig());
        $config->addConfig(['test' => 'foo = baz']);
        $this->assertSame(['test' => ['foo' => 'baz', 'foo2' => 'bar']], $config->getConfig());
        $config->addConfig(['test2' => 'foo = baz']);
        $this->assertSame(
            ['test' => ['foo' => 'baz', 'foo2' => 'bar'], 'test2' => ['foo' => 'baz']],
            $config->getConfig()
        );
    }

    public function test4()
    {
        $config = new Config(new LoggerSimple());
        $config->addConfig(
            [
                'test' => 'foo = bar',
                'test2' => 'foo = bar',
            ]
        );
        $this->assertSame(['test' => ['foo' => 'bar'], 'test2' => ['foo' => 'bar']], $config->getConfig());
    }

    public function test5()
    {
        $config = new Config(new LoggerSimple());
        $config->addConfig(
            [
                'test' => [
                    'foo = bar',
                    'foo = bar2'
                ]
            ]
        );
        $this->assertSame(['test' => ['foo' => 'bar2']], $config->getConfig());
    }

    public function test6()
    {
        $config = new Config(new LoggerSimple());
        $config->addConfig(
            [
                'test' => [
                    'foo = bar',
                    'baz = bar'
                ]
            ]
        );
        $this->assertSame(['test' => ['foo' => 'bar', 'baz' => 'bar']], $config->getConfig());
    }

    /**
     * @throws Exception
     */
    public function testReplace()
    {
        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => 'foo = bar']);
        $config->addConfig(['test' => 'foo = baz']);
        $this->assertSame(['test' => ['foo' => 'baz']], $config->getConfig());

        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => 'foo = bar']);
        $config->addConfig(['test' => 'foo = baz'], [], $config::INI, false);
        $this->assertSame(['test' => ['foo' => 'bar']], $config->getConfig());
    }

    public function testReplaceRecursive()
    {
        $test1 = <<<YAML
bar:
    foo:
        val: 1
        val2: 2
YAML;
        $test2 = <<<YAML
bar:
    foo:
        val: 3
YAML;
        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => $test1], [], $config::YAML);
        $config->addConfig(['test' => $test2], [], $config::YAML);
        $this->assertSame(['test' => ['bar' => ['foo' => ['val' => 3, 'val2' => 2]]]], $config->getConfig());

        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => $test1], [], $config::YAML);
        $config->addConfig(['test' => $test2], [], $config::YAML, false);
        $this->assertSame(['test' => ['bar' => ['foo' => ['val' => 1, 'val2' => 2]]]], $config->getConfig());
    }

    public function testGetNestedValues()
    {
        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => 'foo = bar']);
        $config->addConfig(['test' => 'foo = baz']);
        $this->assertSame('baz', $config->get('test->foo'));
    }

    public function testGetNestedValuesWithCustomSeparator()
    {
        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => 'foo = bar']);
        $config->addConfig(['test' => 'foo = baz']);
        $config->setSeparator('@');
        $this->assertSame('baz', $config->get('test@foo'));
    }

    public function testGetNestedValuesWithReturnDefault()
    {
        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => 'foo = bar']);
        $config->addConfig(['test' => 'foo = baz']);
        $this->assertSame(false, $config->get('test->foo->bar', false));
    }

    public function testValueHandlersYaml()
    {
        define('NOT_ENV', '42');

        $yaml = <<<YAML
env: "%TEST%"
undefined_env: "%UNDEFINED_ENV%"
not_env: %NOT_ENV%
YAML;

        $expect = [
            'env' => 'XXX',
            'undefined_env' => '%UNDEFINED_ENV%',
            'not_env' => 42
        ];

        $config = new Config(new LoggerSimple());
        $config->addConfig(['test' => $yaml], [], Config::YAML);
        $this->assertSame($expect, $config->get('test'));
    }

}
