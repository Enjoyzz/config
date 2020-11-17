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

namespace Enjoys\Config;

use Enjoys\Traits\Options;

/**
 * Description of Parse
 * @psalm-suppress PropertyNotSetInConstructor
 * @author Enjoys
 */
abstract class Parse implements ParseInterface, \Psr\Log\LoggerAwareInterface
{
    use Options;
    use \Psr\Log\LoggerAwareTrait;


    /**
     *
     * @var \Psr\Log\LoggerInterface|null
     */
    protected $logger = null;

    private string $configSource;
//    protected ?array $errors = null;

    public function __construct(string $config)
    {
        $this->configSource = $config;
    }

    public function parse()
    {
        if (!is_file($this->configSource)) {
            return $this->parseString($this->configSource);
        }

        return $this->parseFile($this->configSource);
    }



//    protected function setError(string $error): void
//    {
//        $this->errors[] = $error;
//    }

//    public function getErrors(): ?array
//    {
//        return $this->errors;
//    }

    /**
     * @return mixed
     */
    abstract protected function parseString(string $string);

    /**
     * @return mixed
     */
    abstract protected function parseFile(string $filename);
}
