<?php

use Obelaw\Twist\Addons\AddonRegistrar;
use Obelaw\Obridge\ObridgeAddon;

AddonRegistrar::register(
    'obelaw.twist.obridge',
    ObridgeAddon::class,
    config('obelaw.flow.panels')
);
