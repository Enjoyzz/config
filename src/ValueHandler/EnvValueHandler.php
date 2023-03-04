<?php

declare(strict_types=1);


namespace Enjoys\Config\ValueHandler;


use Enjoys\Config\ValueHandlerInterface;

final class EnvValueHandler implements ValueHandlerInterface
{
    /**
     * @param mixed $value
     * @return  mixed
     * @psalm-suppress InvalidArrayOffset, MixedArgumentTypeCoercion
     */
    public function handle($value)
    {
        if (is_array($value) || is_string($value)) {
            return preg_replace_callback(
                '/(%)([A-Z_]+)(%)/',
                function ($matches) {
                    return
                        $_ENV[$matches[2]]
                        ?? $_SERVER[$matches[2]]
                        ?? (getenv($matches[2]) ?: $matches[1].$matches[2].$matches[3]);
                },
                $value
            );
        }
        return $value;
    }
}
