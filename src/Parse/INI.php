<?php

declare(strict_types=1);

namespace Enjoys\Config\Parse;

use Enjoys\Config\Parse;

/**
 * Class INI
 * @package Enjoys\Config\Parse
 */
class INI extends Parse
{

    /**
     * {@inheritDoc}
     */
    protected function parseString(string $input)
    {
        return parse_ini_string(
            $input,
            (bool) $this->getOption('process_sections', true),
            (int) $this->getOption('scanner_mode', \INI_SCANNER_TYPED)
        );
    }

    /**
     * {@inheritDoc}
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
