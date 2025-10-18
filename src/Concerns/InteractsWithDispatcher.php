<?php

namespace Twist\Concerns;

trait InteractsWithDispatcher
{
    public function pathDispatchers(): string
    {
        return $this->pathDispatchers;
    }
}
