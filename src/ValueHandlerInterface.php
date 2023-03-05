<?php

declare(strict_types=1);


namespace Enjoys\Config;


interface ValueHandlerInterface
{

    /**
     * @param mixed $input
     * @return  mixed
     */
    public function handle($input);
}
