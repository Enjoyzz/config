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

use Enjoys\Config\Parse;

/**
 * Description of INI
 *
 * @author Enjoys
 */
class INI extends Parse
{

    /**
     *
     * @param string $ini
     * @return mixed
     */
    protected function parseString(string $ini)
    {
        return parse_ini_string(
            $ini,
            (bool) $this->getOption('process_sections', true),
            (int) $this->getOption('scanner_mode', \INI_SCANNER_TYPED)
        );
    }

    /**
     *
     * @param string $filename
     * @return mixed
     */
    protected function parseFile(string $filename)
    {
        return parse_ini_file(
            $filename,
            (bool) $this->getOption('process_sections', true),
            (int) $this->getOption('scanner_mode', \INI_SCANNER_TYPED)
        );
    }
}
