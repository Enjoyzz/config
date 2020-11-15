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
 * Description of Config
 *
 * @author Enjoys
 */
class Config
{

    public const YAML = Parse\YAML::class;
    public const INI = Parse\INI::class;
    public const JSON = Parse\Json::class;

    /**
     *
     * @var mixed
     */
    private $config = [];

    public function __construct()
    {
        
    }

    public function addConfig(string $config, array $options = [], string $parseClass = self::INI): void
    {
        if (!class_exists($parseClass)) {
            throw new \Exception(sprintf('Not found parse class: %s', $parseClass));
        }

        $parser = new $parseClass($config);
        $parser->setOptions($options);
        $result  = $parser->parse();
        
        if(is_array($result)){
            $this->config = array_merge($this->config, $result);
        }
    }

    /**
     * 
     * @return mixed
     */
    public function getConfig($key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        if (is_array($this->config) && array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        if (is_object($this->config) && property_exists($this->config, $key)) {
            return $this->config->$key;
        }

        return $default;
    }
}
