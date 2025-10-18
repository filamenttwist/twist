<?php

declare(strict_types=1);

namespace Twist\Console;

use Illuminate\Console\Command;
use Twist\Models\Addon;

final class SetupDisableCommand extends Command
{
    protected $signature = 'twist:setup:disable {addon}';

    protected $description = 'Twist setup';

    public function handle(): void
    {
        $addon = Addon::where('id', $this->argument('addon'))->first();

        if ($addon) {
            $addon->update([
                'is_active' => false,
            ]);

            $this->info('Addon disabled');
        } else {
            $this->error('Addon not found');
        }
    }
}
