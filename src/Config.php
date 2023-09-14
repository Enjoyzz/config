<?php

declare(strict_types=1);

namespace Enjoys\Config;

use Enjoys\Config\ValueHandler\DefinedConstantsValueHandler;
use Enjoys\Config\ValueHandler\EnvValueHandler;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


final class Config
{

    public const YAML = Parse\YAML::class;
    public const INI = Parse\INI::class;
    public const JSON = Parse\Json::class;


    private array $config = [];



    private string $separator = '->';

    private ?LoggerInterface $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param array|string $params
     * @param array $options
     * @param class-string<ParseInterface> $parseClass
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
        $parser = new $parseClass();
        $parser->setOptions($options);
        $parser->setLogger($this->logger);

        /** @var string|string[] $config */
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
                    $this->config[$namespace] = \array_merge_recursive_distinct(
                        (array)$this->config[$namespace],
                        $result
                    );
                } else {
                    $this->config[$namespace] = \array_merge_recursive_distinct(
                        $result,
                        (array)$this->config[$namespace]
                    );
                }
            }
        }
    }


    /**
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        $parts = explode($this->separator, $key);

        try {
            return $this->getValue($parts, $this->config);
        } catch (Exception $e) {
            return $default;
        }
    }

    /**
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key = null, $default = null)
    {
        return $this->getConfig($key, $default);
    }

    /**
     * @param string[] $parts
     * @param mixed $array
     * @return mixed
     * @throws Exception
     */
    private function getValue(array $parts, $array)
    {
        if (!is_array($array)) {
            throw new Exception();
        }

        $key = array_shift($parts);

        if (!array_key_exists($key, $array)) {
            throw new Exception();
        }

        if (count($parts) > 0) {
            return $this->getValue($parts, $array[$key]);
        }

        return $array[$key];
    }

    public function setLogger(?LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param string $separator
     */
    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }


}
