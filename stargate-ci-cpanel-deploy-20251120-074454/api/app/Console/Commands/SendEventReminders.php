<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Log;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send-reminders {--test : Send test reminders for debugging}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for upcoming events to registered users';

    protected EventRegistrationService $registrationService;

    /**
     * Create a new command instance.
     */
    public function __construct(EventRegistrationService $registrationService)
    {
        parent::__construct();
        $this->registrationService = $registrationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting event reminder process...');

        try {
            if ($this->option('test')) {
                $this->info('Running in test mode...');
                $this->sendTestReminders();
            } else {
                $this->registrationService->scheduleReminders();
                $this->info('Event reminders scheduled successfully.');
            }

            Log::info('Event reminders command executed successfully');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to send event reminders: ' . $e->getMessage());
            Log::error('Event reminders command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Send test reminders for debugging
     */
    private function sendTestReminders()
    {
        $this->info('Sending test reminders...');
        
        // Get all pending registrations
        $registrations = \App\Models\EventRegistration::where('status', 'registered')
            ->whereNull('reminder_sent_at')
            ->with('event')
            ->limit(5)
            ->get();

        if ($registrations->isEmpty()) {
            $this->warn('No pending registrations found for test reminders.');
            return;
        }

        $sentCount = 0;
        foreach ($registrations as $registration) {
            try {
                $this->registrationService->sendReminderEmail($registration);
                $registration->markReminderSent();
                $sentCount++;
                
                $this->line("✓ Sent test reminder to: {$registration->email} for event: {$registration->event->title}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder to {$registration->email}: " . $e->getMessage());
            }
        }

        $this->info("Test reminders sent: {$sentCount}/{$registrations->count()}");
    }
}