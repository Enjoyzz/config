<?php

declare(strict_types=1);

namespace Tests\Enjoys\Config\ValueHandler;

use Enjoys\Config\ValueHandler\EnvValueHandler;
use PHPUnit\Framework\TestCase;

class EnvValueHandlerTest extends TestCase
{

    protected function setUp(): void
    {
        $_ENV['TEST'] = 'XXX';
        $_ENV['test2'] = 'XXX';
        $_ENV['TEST3'] = 'XXX3';
    }

    protected function tearDown(): void
    {
        unset($_ENV['TEST'], $_ENV['test2'], $_ENV['TEST3']);
    }

    /**
     * @dataProvider dataForTestHandler
     */
    public function testHandler($expect, $value)
    {
        $handler = new EnvValueHandler();
        $this->assertSame($expect, $handler->handle($value));
    }

    public function dataForTestHandler()
    {
        return [
            ['XXX/test', '%TEST%/test'],
            ['%test2%/test', '%test2%/test'],
            ['TEST/test', 'TEST/test'],
            ['yyy/XXX/test', 'yyy/%TEST%/test'],
            ['yyy/XXX3/test', 'yyy/%TEST3%/test'],
            ['XXX/XXX/XXX/XXX/test', '%TEST%/%TEST%/%TEST%/%TEST%/test'],
            ['%UNDEFINED_ENV%', '%UNDEFINED_ENV%']
        ];
    }
}
