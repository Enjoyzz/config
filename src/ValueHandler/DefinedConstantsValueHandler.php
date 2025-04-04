<?php

declare(strict_types=1);


namespace Enjoys\Config\ValueHandler;


use Enjoys\Config\ValueHandlerInterface;

final class DefinedConstantsValueHandler implements ValueHandlerInterface
{

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    public function handle(string $input): string
    {
        return preg_replace_callback(
            '/(%)(.+)(%)/',
            function ($matches) {
                if (\defined($matches[2])) {
                    return constant($matches[2]);
                }
                return $matches[1] . $matches[2] . $matches[3];
            },
            $input
        ) ?? '';
    }
}
