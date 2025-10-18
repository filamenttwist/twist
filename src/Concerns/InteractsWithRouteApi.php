<?php

namespace Twist\Concerns;

trait InteractsWithRouteApi
{
    public function pathRouteApi(): string
    {
        return $this->pathRouteApi;
    }
}