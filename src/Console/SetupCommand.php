<?php

declare(strict_types=1);

namespace Obelaw\Twist\Console;

use Illuminate\Console\Command;
use Obelaw\Twist\Addons\AddonRegistrar;
use Obelaw\Twist\Addons\AddonsPool;
use Obelaw\Twist\Models\Addon;

final class SetupCommand extends Command
{
    protected $signature = 'twist:setup';

    protected $description = 'Twist setup';

    public function handle(): void
    {
        if (AddonsPool::hasPools()) {
            AddonsPool::scan();

            foreach (AddonRegistrar::getPaths() as $id => $pointer) {
                Addon::updateOrCreate([
                    'id' => $id,
                ], [
                    'id' => $id,
                    'pointer' => $pointer,
                ]);
            }

            $this->info('Twist setup completed');
        } else {
            $this->error('No addons path found');
        }
    }
}
