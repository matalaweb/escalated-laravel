<?php

namespace Escalated\Laravel\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'escalated:install
        {--force : Overwrite existing files}
        {--config : Only publish configuration}
        {--migrations : Only publish migrations}
        {--client-assets : Only publish client Vue pages}
        {--admin-assets : Only publish admin Vue pages}';

    protected $description = 'Install the Escalated support ticket system';

    public function handle(): int
    {
        $this->info('Installing Escalated...');
        $this->newLine();

        $force = $this->option('force');
        $onlyConfig = $this->option('config');
        $onlyMigrations = $this->option('migrations');
        $onlyClient = $this->option('client-assets');
        $onlyAdmin = $this->option('admin-assets');
        $publishAll = ! $onlyConfig && ! $onlyMigrations && ! $onlyClient && ! $onlyAdmin;

        if ($publishAll || $onlyConfig) {
            $this->publishConfig($force);
        }

        if ($publishAll || $onlyMigrations) {
            $this->publishMigrations($force);
        }

        if ($publishAll || $onlyClient) {
            $this->publishClientAssets($force);
        }

        if ($publishAll || $onlyAdmin) {
            $this->publishAdminAssets($force);
        }

        if ($publishAll) {
            $this->publishEmailViews($force);
        }

        $this->newLine();
        $this->outputSetupInstructions();

        return self::SUCCESS;
    }

    protected function publishConfig(bool $force): void
    {
        $this->components->task('Publishing configuration', function () use ($force) {
            $this->callSilently('vendor:publish', [
                '--tag' => 'escalated-config',
                '--force' => $force,
            ]);
        });
    }

    protected function publishMigrations(bool $force): void
    {
        $this->components->task('Publishing migrations', function () use ($force) {
            $this->callSilently('vendor:publish', [
                '--tag' => 'escalated-migrations',
                '--force' => $force,
            ]);
        });
    }

    protected function publishClientAssets(bool $force): void
    {
        $this->components->task('Publishing client assets', function () use ($force) {
            $this->callSilently('vendor:publish', [
                '--tag' => 'escalated-client-assets',
                '--force' => $force,
            ]);
        });
    }

    protected function publishAdminAssets(bool $force): void
    {
        $this->components->task('Publishing admin assets', function () use ($force) {
            $this->callSilently('vendor:publish', [
                '--tag' => 'escalated-admin-assets',
                '--force' => $force,
            ]);
        });
    }

    protected function publishEmailViews(bool $force): void
    {
        $this->components->task('Publishing email views', function () use ($force) {
            $this->callSilently('vendor:publish', [
                '--tag' => 'escalated-views',
                '--force' => $force,
            ]);
        });
    }

    protected function outputSetupInstructions(): void
    {
        $this->components->info('Escalated installed successfully!');
        $this->newLine();

        $this->line('  Next steps:');
        $this->newLine();
        $this->line('  1. Implement the Ticketable interface on your User model:');
        $this->newLine();
        $this->line('     use Escalated\Laravel\Contracts\HasTickets;');
        $this->line('     use Escalated\Laravel\Contracts\Ticketable;');
        $this->newLine();
        $this->line('     class User extends Authenticatable implements Ticketable');
        $this->line('     {');
        $this->line('         use HasTickets;');
        $this->line('     }');
        $this->newLine();
        $this->line('  2. Define authorization gates in your AuthServiceProvider:');
        $this->newLine();
        $this->line('     Gate::define(\'escalated-admin\', fn ($user) => $user->is_admin);');
        $this->line('     Gate::define(\'escalated-agent\', fn ($user) => $user->is_agent);');
        $this->newLine();
        $this->line('  3. Run migrations:');
        $this->newLine();
        $this->line('     php artisan migrate');
        $this->newLine();
        $this->line('  4. Visit /support to see the customer portal');
        $this->newLine();
    }
}
