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

namespace Enjoys\Config\Parse;

use \Symfony\Component\Yaml as Symfony;

/**
 * @see https://symfony.com/doc/current/components/yaml.htm
 * @author Enjoys
 */
class YAML extends \Enjoys\Config\Parse
{

    /**
     * {@inheritDoc}
     * 
     * @param string $yaml
     * @return mixed
     */
    protected function parseString(string $yaml)
    {
        try {
            return Symfony\Yaml::parse($yaml, (int) $this->getOption('flags', 0));
        } catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
            throw new \Enjoys\Config\ParseException($e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     * 
     * @param string $filename
     * @return mixed
     */
    protected function parseFile(string $filename)
    {
        try {
            return Symfony\Yaml::parseFile($filename, (int) $this->getOption('flags', 0));
        } catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
            throw new \Enjoys\Config\ParseException($e->getMessage());
        }
    }
}
