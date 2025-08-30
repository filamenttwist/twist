<?php

declare(strict_types=1);

namespace Obelaw\Twist\Console;

use Illuminate\Console\Command;
use Obelaw\Twist\Addons\AddonsPool;

final class MakeCommand extends Command
{
    protected $signature = 'twist:make 
                            {folder : Folder name to create}
                            {--all : Create in all pool paths}
                            {--group= : Parent folder to use for LEVELTWO pools}';

    protected $description = 'Create addon structure in the addons pool';

    public function handle(): void
    {
        if (AddonsPool::hasPools()) {
            AddonsPool::scan();

            $folder = trim((string) $this->argument('folder'));
            if ($folder === '') {
                $this->error('Folder name is required');
                return;
            }

            $pools = AddonsPool::getPoolPaths();
            if (empty($pools)) {
                $this->error('No addons path found');
                return;
            }

            $selectedPools = $pools;

            if (!$this->option('all')) {
                if (count($pools) > 1) {
                    $labels = array_map(
                        fn($p) => $p['path'] . ' ' . ($p['level'] ?? ''),
                        $pools
                    );

                    $choice = $this->choice('Select pool path', $labels);
                    $index = array_search($choice, $labels, true);

                    if ($index === false) {
                        $this->error('Invalid selection');
                        return;
                    }

                    $selectedPools = [$pools[$index]];
                } else {
                    $selectedPools = [$pools[0]];
                }
            }

            $created = [];
            $skipped = [];
            $stubPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'twist.php.stub';

            if (!is_file($stubPath)) {
                $this->error("Stub not found: {$stubPath}");
                return;
            }

            $stub = file_get_contents($stubPath);
            if ($stub === false) {
                $this->error("Cannot read stub: {$stubPath}");
                return;
            }

            // Prepare replacements
            $id = strtolower(preg_replace('/[^a-z0-9\-_\.]/i', '-', $folder));
            $classReplacement = 'TwistAddon::class';

            foreach ($selectedPools as $pool) {
                $basePath = rtrim($pool['path'], DIRECTORY_SEPARATOR);
                $level = $pool['level'] ?? AddonsPool::LEVELTWO;

                // Determine target path based on pool level
                if ($level === AddonsPool::LEVELONE) {
                    $target = $basePath . DIRECTORY_SEPARATOR . $folder;
                } else {
                    // LEVELTWO -> require a parent/group directory
                    $group = $this->option('group') ?: $this->ask('Parent folder for LEVELTWO pool');
                    $group = trim((string) $group);
                    $group = ucfirst(preg_replace('/[^a-z0-9\-_\.]/i', '-', $group));
                    $target = $basePath . DIRECTORY_SEPARATOR . $group . DIRECTORY_SEPARATOR . $folder;
                }

                if (!is_dir($target)) {
                    if (!@mkdir($target, 0775, true) && !is_dir($target)) {
                        $this->error("Failed to create: {$target}");
                        continue;
                    }
                    $created[] = $target;
                } else {
                    $skipped[] = $target;
                }

                // Fill stub placeholders and create twist.php inside the target folder
                $twistFile = $target . DIRECTORY_SEPARATOR . 'twist.php';
                if (!is_file($twistFile)) {
                    $filled = str_replace(['{{ ID }}', '{{ CLASS }}'], [$id, $classReplacement], $stub);
                    if (file_put_contents($twistFile, $filled) === false) {
                        $this->error("Failed to write: {$twistFile}");
                        continue;
                    }
                    $this->info("Created file: {$twistFile}");
                } else {
                    $this->line("File exists: {$twistFile}");
                }
            }

            if (!empty($created)) {
                $this->info('Created folders:');
                foreach ($created as $path) {
                    $this->line($path);
                }
            }

            if (!empty($skipped)) {
                $this->warn('Folders already exist:');
                foreach ($skipped as $path) {
                    $this->line($path);
                }
            }
        } else {
            $this->error('No addons path found');
        }
    }
}
