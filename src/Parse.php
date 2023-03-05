<?php

declare(strict_types=1);

namespace Enjoys\Config;

use Enjoys\Config\ValueHandler\DefinedConstantsValueHandler;
use Enjoys\Config\ValueHandler\EnvValueHandler;
use Enjoys\Traits\Options;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Description of Parse
 * @author Enjoys
 */
abstract class Parse implements ParseInterface
{
    use Options;

    private ?string $configSource = null;
    protected LoggerInterface $logger;

    private array $valueHandlers = [
        EnvValueHandler::class,
        DefinedConstantsValueHandler::class
    ];

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

    /**
     * @return array|false|null
     */
    public function parse()
    {
        if (is_null($this->configSource)) {
            $this->logger->notice('Add data for parsing');
            return null;
        }

        if (!is_file($this->configSource)) {
            return $this->parseString($this->applyValueHandlers($this->configSource));
        }

        return $this->parseFile($this->configSource);
    }

    protected function applyValueHandlers($data)
    {
        /** @var class-string<ValueHandlerInterface> $valueHandler */
        foreach ($this->valueHandlers as $valueHandler) {
            $data = (new $valueHandler())->handle($data);
        }
      //  var_dump($data);
        return $data;
    }

    /**
     * @param string $filename
     * @return array|null|false
     */
    private function parseFile(string $filename)
    {
        $data = file_get_contents($filename);
        return $this->parseString($this->applyValueHandlers($data));
    }

    /**
     * @param string $input
     * @return array|null|false
     */
    abstract protected function parseString(string $input);


}
