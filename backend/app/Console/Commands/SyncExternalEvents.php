<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventsService;
use Illuminate\Support\Facades\Log;

class SyncExternalEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:sync {--source= : Sync specific source (openai, gemini, cohere, softbank, oracle, mgx)} {--force : Force refresh even if cache is valid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync events from external APIs';

    protected EventsService $eventsService;

    /**
     * Create a new command instance.
     */
    public function __construct(EventsService $eventsService)
    {
        parent::__construct();
        $this->eventsService = $eventsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->option('source');
        $force = $this->option('force');

        $this->info('Starting external events sync...');

        try {
            if ($source) {
                $this->syncSpecificSource($source, $force);
            } else {
                $this->syncAllSources($force);
            }

            Log::info('External events sync completed successfully');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to sync external events: ' . $e->getMessage());
            Log::error('External events sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Sync all external sources
     */
    private function syncAllSources(bool $force = false)
    {
        $this->info('Syncing all external sources...');

        if ($force) {
            $this->info('Force refresh enabled - clearing all caches...');
            $results = $this->eventsService->forceRefreshAllEvents();
        } else {
            $results = $this->eventsService->syncAllExternalEvents();
        }

        $this->displayResults($results);
    }

    /**
     * Sync specific source
     */
    private function syncSpecificSource(string $source, bool $force = false)
    {
        $this->info("Syncing events from {$source}...");

        $method = 'sync' . ucfirst($source) . 'Events';
        
        if (!method_exists($this->eventsService, $method)) {
            $this->error("Unknown source: {$source}");
            return;
        }

        if ($force) {
            $this->info('Force refresh enabled - clearing cache...');
            $cacheKey = strtolower($source) . '_events_sync';
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
        }

        $result = $this->eventsService->$method();
        $this->displaySingleResult($result);
    }

    /**
     * Display sync results
     */
    private function displayResults(array $results)
    {
        $this->newLine();
        $this->info('=== SYNC RESULTS ===');
        
        $totalSynced = 0;
        $successCount = 0;
        $errorCount = 0;

        foreach ($results as $key => $result) {
            if ($key === 'total_synced' || $key === 'errors') {
                continue;
            }

            if (is_array($result) && isset($result['status'])) {
                $status = $result['status'];
                $count = $result['count'] ?? 0;
                $source = $result['source'] ?? $key;

                if ($status === 'success') {
                    $this->line("✓ {$source}: {$count} events synced");
                    $successCount++;
                    $totalSynced += $count;
                } else {
                    $error = $result['error'] ?? 'Unknown error';
                    $this->error("✗ {$source}: Failed - {$error}");
                    $errorCount++;
                }
            }
        }

        $this->newLine();
        $this->info("Total events synced: {$totalSynced}");
        $this->info("Successful sources: {$successCount}");
        $this->info("Failed sources: {$errorCount}");
    }

    /**
     * Display single source result
     */
    private function displaySingleResult(array $result)
    {
        $this->newLine();
        $this->info('=== SYNC RESULT ===');
        
        $status = $result['status'];
        $count = $result['count'] ?? 0;
        $source = $result['source'] ?? 'Unknown';

        if ($status === 'success') {
            $this->line("✓ {$source}: {$count} events synced successfully");
        } else {
            $error = $result['error'] ?? 'Unknown error';
            $this->error("✗ {$source}: Failed - {$error}");
        }
    }
}
