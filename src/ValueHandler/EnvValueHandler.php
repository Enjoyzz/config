<?php

declare(strict_types=1);


namespace Enjoys\Config\ValueHandler;


use Enjoys\Config\ValueHandlerInterface;

final class EnvValueHandler implements ValueHandlerInterface
{
    /**

     * @psalm-suppress InvalidArrayOffset, MixedArgumentTypeCoercion
     */
    public function handle(string $input): string
    {

        return preg_replace_callback(
            '/(%)([A-Z_0-9]+)(%)/',
            function (array $matches) {
                return
                    $_ENV[$matches[2]]
                    ?? $_SERVER[$matches[2]]
                    ?? (getenv($matches[2]) === false ? $matches[1] . $matches[2] . $matches[3] : getenv($matches[2]));
            },
            $input
        ) ?? '';
    }
}
