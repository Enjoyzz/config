<?php

declare(strict_types=1);

namespace Enjoys\Config\Parse;

use Enjoys\Config\Parse;
use Symfony\Component\Yaml as Symfony;

/**
 * @see https://symfony.com/doc/current/components/yaml.htm
 * @psalm-suppress MixedReturnStatement, MixedInferredReturnType
 * @author Enjoys
 */
class YAML extends Parse
{

    /**
     * {@inheritDoc}
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

}
