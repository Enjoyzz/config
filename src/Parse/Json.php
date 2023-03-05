<?php

declare(strict_types=1);

namespace Enjoys\Config\Parse;

use Enjoys\Config\Parse;

/**
 * Class Json
 * @package Enjoys\Config\Parse
 */
class Json extends Parse
{

    /**
     * {@inheritDoc}
     */
    protected function parseString(string $input)
    {
        /** @var array $result */
        $result = \json_decode(
            $input,
            true,
            (int)$this->getOption('depth', 512),
            (int)$this->getOption('options', 0)
        );

        //Clear the most recent error
        \error_clear_last();

        if (\json_last_error() === JSON_ERROR_NONE) {
            return $result;
        }

        $this->logger->error(sprintf('(%s) %s', \json_last_error(), \json_last_error_msg()));

        return null;
    }

}
