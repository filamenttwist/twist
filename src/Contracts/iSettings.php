<?php

namespace Obelaw\Twist\Contracts;

interface iSettings
{
    public function mount(): void;

    public function save($inputs);
}
