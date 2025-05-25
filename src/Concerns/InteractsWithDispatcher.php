<?php

namespace Obelaw\Twist\Concerns;

trait InteractsWithDispatcher
{
    public function pathDispatchers(): string
    {
        return $this->pathDispatchers;
    }
}
