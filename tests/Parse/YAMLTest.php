<?php

/*
 * The MIT License
 *
 * Copyright 2020 Enjoys.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Tests\Enjoys\Config;

/**
 * Description of YAMLTest
 *
 * @author Enjoys
 */
class YAMLTest extends \PHPUnit\Framework\TestCase
{

    public function test1()
    {
        $config = 'foo: bar';
        $parser = new \Enjoys\Config\Parse\YAML($config);
        $this->assertSame('bar', $parser->parse()['foo']);
    }

    public function test2()
    {
        $parser = new \Enjoys\Config\Parse\YAML(__DIR__ . '/../fixtures/yaml/yaml1.yml');
        $this->assertSame('d', $parser->parse()['a']['b']);
    }

    public function test3()
    {
        $this->expectException(\Enjoys\Config\ParseException::class);
        $parser = new \Enjoys\Config\Parse\YAML(__DIR__ . '/../fixtures/yaml/yaml2.yml');
        $parser->parse();
    }
}
