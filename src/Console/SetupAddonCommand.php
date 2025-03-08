<?php

declare(strict_types=1);

namespace Obelaw\Twist\Console;

use Illuminate\Console\Command;
use Obelaw\Twist\Models\Addon;

final class SetupAddonCommand extends Command
{
    protected $signature = 'twist:setup:addon {id} {pointer}';

    protected $description = 'Twist setup a new addon';

    public function handle(): void
    {
        Addon::updateOrCreate([
            'id' => $this->argument('id'),
        ], [
            'id' => $this->argument('id'),
            'pointer' => $this->argument('pointer'),
        ]);

        $this->info('Addon setup completed');
    }
}
