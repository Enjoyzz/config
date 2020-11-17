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

/**
 * Class Logger
 *
 * @author Enjoys
 */
class Logger implements \Psr\Log\LoggerInterface
{
    use \Psr\Log\LoggerTrait;

    public function log($level, $message, array $context = [])
    {
        if ($context) {
            $message = \strtr($message, \iterator_to_array(self::context2replacements($context), true));
        }

        // В целях отладки приведём XML в читаемый вид, разбив по тегам.
        if (\strpos($message, '><') !== false) {
            $message = \str_replace('><', ">\n<", $message);
        }

        \fwrite(\STDERR, "\n{$message}\n\n");
    }

    /**
     * @param array<string, string> $context
     */
    private static function context2replacements($context): \Generator
    {
        foreach ($context as $key => $value) {
            yield '{' . $key . '}' => $value;
        }
    }
}
