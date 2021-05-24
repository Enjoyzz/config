<?php

declare(strict_types=1);

namespace Enjoys\Config\Parse;

use Enjoys\Config\Parse;
use Symfony\Component\Yaml as Symfony;

/**
 * @see https://symfony.com/doc/current/components/yaml.htm
 * @author Enjoys
 */
class YAML extends Parse
{

    /**
     * {@inheritDoc}
     *
     * @param string $input
     * @return mixed
     */
    protected function parseString(string $input)
    {
        try {
            return Symfony\Yaml::parse($input, (int)$this->getOption('flags', 0));
        } catch (Symfony\Exception\ParseException $e) {
            $this->logger->error($e->getMessage());
            return null;
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
            return Symfony\Yaml::parseFile($filename, (int)$this->getOption('flags', 0));
        } catch (Symfony\Exception\ParseException $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }
}
