<?php

declare(strict_types=1);

namespace Enjoys\Config;

use Enjoys\Config\ValueHandler\DefinedConstantsValueHandler;
use Enjoys\Config\ValueHandler\EnvValueHandler;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class Parse implements ParseInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $options = [];

    private ?string $configSource = null;
    protected ?LoggerInterface $logger = null;

    private array $valueHandlers = [
        EnvValueHandler::class,
        DefinedConstantsValueHandler::class
    ];


    public function setLogger(?LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @param array<string, mixed> $options
     * @return $this
     * @psalm-suppress MixedAssignment
     */
    public function setOptions(array $options = []): self
    {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }
        return $this;
    }

    /**
     * @param mixed $value
     */
    public function setOption(string $key, $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param mixed $defaults
     * @return mixed
     */
    public function getOption(string $key, $defaults = null)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }
        return $defaults;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
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
            if ($this->logger instanceof LoggerInterface){
                $this->logger->notice('Add data for parsing');
            }
            return null;
        }

        if (!is_file($this->configSource)) {
            return $this->parseString($this->applyValueHandlers($this->configSource));
        }

        return $this->parseFile($this->configSource);
    }

    private function applyValueHandlers(string $data): string
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
        return $this->parseString($this->applyValueHandlers($data === false ? '' : $data));
    }

    /**
     * @param string $input
     * @return array|null|false
     */
    abstract protected function parseString(string $input);


}
