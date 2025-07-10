<?php

declare(strict_types=1);

namespace Obelaw\Twist\Console;

use function Laravel\Prompts\confirm;
use Illuminate\Console\Command;
use Obelaw\Twist\Models\Addon;

final class SetupClearCommand extends Command
{
    protected $signature = 'twist:setup:clear';

    protected $description = 'Twist setup clear';

    public function handle(): void
    {
        $confirmed = confirm('Are you sure you want to clear the setup?');

        if ($confirmed) {
            Addon::truncate();
            $this->info('Twist setup clear completed');
        }
    }
}
