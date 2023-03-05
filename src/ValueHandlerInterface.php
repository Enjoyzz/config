<?php

declare(strict_types=1);


namespace Enjoys\Config;


interface ValueHandlerInterface
{

    public function handle(string $input): string;
}
