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
 * Class LoggerSimple
 *
 * @author Enjoys
 */
class LoggerSimple implements \Psr\Log\LoggerInterface
{
    use \Psr\Log\LoggerTrait;

    protected array $errors = [];

    public function log($level, $message, array $context = [])
    {

        // throw new \Enjoys\Config\ParseException();
        $this->setError($level, $message);
    }

    /**
     * @param string $level
     * @param string $message
     * @return void
     */
    public function setError(string $level, string $message): void
    {
        $this->errors[$level][] = $message;
    }

    /**
     * @param string|null $level
     * @return array
     */
    public function getError(?string $level = null): array
    {
        if (array_key_exists($level, $this->errors)) {
            return $this->errors[$level];
        }

        return $this->errors;
    }
}
