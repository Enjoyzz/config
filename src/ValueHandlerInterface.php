<?php

declare(strict_types=1);


namespace Enjoys\Config;


interface ValueHandlerInterface
{

    /**
     * @param mixed $value
     * @return  mixed
     */
    public function handle($value);
}
