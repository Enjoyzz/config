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

use Enjoys\Config\Parse;
use Exception;

/**
 * Description of Config

 * @author Enjoys
 */
class Config implements \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    public const YAML = Parse\YAML::class;
    public const INI = Parse\INI::class;
    public const JSON = Parse\Json::class;

    /**
     *
     * @var \Psr\Log\LoggerInterface|null
     */
    protected $logger = null;

    /**
     *
     * @var mixed
     */
    private $config = [];

    public function __construct(?\Psr\Log\LoggerInterface $logger = null)
    {
        if ($logger !== null) {
            $this->setLogger($logger);
        }
    }

    public function addConfig($config, array $options = [], string $parseClass = self::INI): void
    {
        $namespace = null;

        if (!class_exists($parseClass)) {
            throw new \Exception(sprintf('Not found parse class: %s', $parseClass));
        }

        if (is_array($config)) {
            $namespace = array_key_first($config);
            $config = $config[$namespace];
        }


        /** @var  ParseInterface $parser */
        $parser = new $parseClass($config);
        $parser->setOptions($options);


        if ($this->logger) {
            $parser->setLogger($this->logger);
        }

        $result = $parser->parse();


        if (is_array($result)) {
            if ($namespace === null) {
                $this->config = array_merge($this->config, $result);
            } else {
                if (!array_key_exists($namespace, $this->config)) {
                    $this->config[$namespace] = [];
                }
                $this->config[$namespace] = array_merge($this->config[$namespace], $result);
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

        if (is_array($this->config) && array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

//        if (is_object($this->config) && property_exists($this->config, $key)) {
//            return $this->config->$key;
//        }

        return $default;
    }
}
