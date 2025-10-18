<?php

declare(strict_types=1);

namespace Twist\Console;

use Illuminate\Console\Command;
use Twist\Models\Addon;

final class SetupEnableCommand extends Command
{
    protected $signature = 'twist:setup:enable {addon}';

    protected $description = 'Twist setup';

    public function handle(): void
    {
        $addon = Addon::where('id', $this->argument('addon'))->first();

        if ($addon) {
            $addon->update([
                'is_active' => true,
            ]);

            $this->info('Addon enabled');
        } else {
            $this->error('Addon not found');
        }
    }
}
