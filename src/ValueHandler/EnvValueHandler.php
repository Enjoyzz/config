<?php

declare(strict_types=1);


namespace Enjoys\Config\ValueHandler;


use Enjoys\Config\ValueHandlerInterface;

final class EnvValueHandler implements ValueHandlerInterface
{
    /**
     * @param mixed $input
     * @return  mixed
     * @psalm-suppress InvalidArrayOffset, MixedArgumentTypeCoercion
     */
    public function handle($input)
    {
        if (!is_string($input)) {
            return $input;
        }

        return preg_replace_callback(
            '/(%)([A-Z_]+)(%)/',
            function ($matches) {
                return
                    $_ENV[$matches[2]]
                    ?? $_SERVER[$matches[2]]
                    ?? (getenv($matches[2]) ?: $matches[1] . $matches[2] . $matches[3]);
            },
            $input
        );
    }
}
