<?php

declare(strict_types=1);

namespace Twist\Console;

use Illuminate\Console\Command;
use Twist\Addons\AddonRegistrar;
use Twist\Addons\AddonsPool;
use Twist\Models\Addon;

final class SetupCommand extends Command
{
    protected $signature = 'twist:setup';

    protected $description = 'Twist setup';

    public function handle(): void
    {
        if (AddonsPool::hasPools()) {
            AddonsPool::scan();

            foreach (AddonRegistrar::getPaths() as $id => $addon) {
                // dump($addon);
                Addon::updateOrCreate([
                    'id' => $id,
                ], [
                    'id' => $id,
                    'pointer' => $addon['path'],
                    'panels' => $addon['panels'],
                ]);
            }

            $this->info('Twist setup completed');
        } else {
            $this->error('No addons path found');
        }
    }
}
