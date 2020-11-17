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

namespace Tests\Enjoys\Config\Parse;

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
        $logger = new \Tests\Enjoys\Config\LoggerSimple();
        $parser->setLogger($logger);
        $this->assertSame('d', $parser->parse()['a']['b']);
        $this->assertSame([], $logger->getError());
    }

    public function test3()
    {
        //$this->expectException(\Enjoys\Config\ParseException::class);
        $parser = new \Enjoys\Config\Parse\YAML(__DIR__ . '/../fixtures/yaml/yaml2.yml');
        $logger = new \Tests\Enjoys\Config\LoggerSimple();
        $parser->setLogger($logger);
        $this->assertSame(null, $parser->parse());
        $this->assertSame(['Unable to parse at line 3 (near "e").'], $logger->getError('error'));
    }

    public function test4()
    {
        //$this->expectException(\Enjoys\Config\ParseException::class);
        $config = "foo: bar\n    baz\nzed";
        $parser = new \Enjoys\Config\Parse\YAML($config);
        $logger = new \Tests\Enjoys\Config\LoggerSimple();
        $parser->setLogger($logger);
        $this->assertSame(null, $parser->parse());
        $this->assertSame(['Unable to parse at line 3 (near "zed").'],$logger->getError('error'));
    }

    public function test5()
    {

        $yaml = <<<YAML
event1:
  name: My Event
  php_int_size: !php/const PHP_INT_SIZE
  mydate: 2020-11-16
  mydatestring: !!str 2020-11-16
YAML;
        $parser = new \Enjoys\Config\Parse\YAML($yaml);
        $parser->setOption('flags', \Symfony\Component\Yaml\Yaml::PARSE_CUSTOM_TAGS | \Symfony\Component\Yaml\Yaml::PARSE_CONSTANT );



        $result = $parser->parse();
        $this->assertSame(\PHP_INT_SIZE, $result['event1']['php_int_size']);
        $this->assertSame(1605484800, $result['event1']['mydate']);
        $this->assertSame('2020-11-16', $result['event1']['mydatestring']);
    }
}
