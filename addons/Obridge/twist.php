<?php

use Obelaw\Obridge\ObridgeAddon;

\Obelaw\Twist\Addons\AddonRegistrar::register(
    'obelaw.twist.obridge',
    ObridgeAddon::class,
    config('obelaw.flow.panels')
);
