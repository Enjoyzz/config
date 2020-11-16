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
/**
 * Description of ConfigTest
 *
 * @author Enjoys
 */
class ConfigTest extends PHPUnit\Framework\TestCase
{
    public function test1()
    {
        $config = new Enjoys\Config\Config();
        $config->addConfig('foo = bar');
        $this->assertSame(['foo' => 'bar'], $config->getConfig());
        $this->assertSame('bar', $config->getConfig('foo'));
        $this->assertSame(false, $config->getConfig('baz', false));
    }
    
    
    public function test2()
    {
        $this->expectException(\Exception::class);
        $config = new Enjoys\Config\Config();
        $config->addConfig('foo = bar', [], 'InvalidClass');
    }
}