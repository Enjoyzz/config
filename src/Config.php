<?php

declare(strict_types=1);

namespace Enjoys\Config;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


/**
 * Description of Config
 * @author Enjoys
 */
final class Config
{

    public const YAML = Parse\YAML::class;
    public const INI = Parse\INI::class;
    public const JSON = Parse\Json::class;


    private array $config = [];

    private LoggerInterface $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     *
     * @param array|string $params
     * @param array $options
     * @param string $parseClass
     * @param bool $replace
     * @return void
     * @throws \Exception
     */
    public function addConfig($params, array $options = [], string $parseClass = self::INI, bool $replace = true): void
    {
        $params = (array)$params;

        if (!class_exists($parseClass)) {
            throw new \Exception(sprintf('Not found parse class: %s', $parseClass));
        }
        /** @var  ParseInterface $parser */
        $parser = new $parseClass();
        $parser->setOptions($options);
        $parser->setLogger($this->logger);

        foreach ($params as $namespace => $config) {
            if (is_int($namespace)) {
                $namespace = null;
            }

            if (is_string($namespace)) {
                $namespace = \trim($namespace);
            }

            if (is_array($config)) {
                foreach ($config as $_config) {
                    $this->parse($parser, $_config, $namespace, $replace);
                }
                continue;
            }
            $this->parse($parser, $config, $namespace, $replace);
        }
    }

    private function parse(
        ParseInterface $parser,
        string $config,
        ?string $namespace = null,
        bool $replace = true
    ): void {
        $parser->addConfigSource($config);

        $result = $parser->parse();

        if (is_array($result)) {
            if ($namespace === null) {
                if ($replace === true) {
                    $this->config = \array_merge_recursive_distinct($this->config, $result);
                } else {
                    $this->config = \array_merge_recursive_distinct($result, $this->config);
                }
            } else {
                if (!array_key_exists($namespace, $this->config)) {
                    $this->config[$namespace] = [];
                }
                if ($replace === true) {
                    $this->config[$namespace] = \array_merge_recursive_distinct((array)$this->config[$namespace], $result);
                } else {
                    $this->config[$namespace] = \array_merge_recursive_distinct($result, (array)$this->config[$namespace]);
                }
            }
        }
    }


    /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return $default;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
