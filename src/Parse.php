<?php

declare(strict_types=1);

namespace Enjoys\Config;

use Enjoys\Traits\Options;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Description of Parse
 * @psalm-suppress PropertyNotSetInConstructor
 * @author Enjoys
 */
abstract class Parse implements ParseInterface
{
    use Options;

    private ?string $configSource = null;
    protected LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function addConfigSource(string $source): void
    {
        $this->configSource = $source;
    }

    public function parse()
    {
        if (is_null($this->configSource)) {
            $this->logger->error('Добавьте данные для парсинга');
            return null;
        }

        if (!is_file($this->configSource)) {
            return $this->parseString($this->configSource);
        }

        return $this->parseFile($this->configSource);
    }

    /**
     * @param string $input
     * @return mixed
     */
    abstract protected function parseString(string $input);

    /**
     * @param string $filename
     * @return mixed
     */
    abstract protected function parseFile(string $filename);
}
