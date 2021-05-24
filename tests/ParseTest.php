<?php

declare(strict_types=1);

namespace Tests\Enjoys\Config;

use Enjoys\Config\Parse;
use PHPUnit\Framework\TestCase;

class ParseTest extends TestCase
{

    public function testParseNull()
    {
        $parser = new Parse\INI();
        $this->assertNull($parser->parse());
    }
}
