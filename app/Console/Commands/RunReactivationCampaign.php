<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunReactivationCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-reactivation-campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identifică clienții inactivi și le trimite un voucher de invitație.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enabled = \App\Models\Setting::getValue('reactivation_enabled', '0');
        if ($enabled != '1') {
            $this->info('Campania de reactivare este dezactivată.');
            return;
        }

        $daysInactive = (int) \App\Models\Setting::getValue('reactivation_days_inactive', '60');
        $percent = \App\Models\Setting::getValue('reactivation_discount_percent', '15');
        $validDays = (int) \App\Models\Setting::getValue('reactivation_voucher_valid_days', '10');
        $businessName = \App\Models\Setting::getValue('business_name', 'Salonul Nostru');

        // Find clients who haven't booked in the last X days
        // Anti-spam: Only send once every 30 days
        $thresholdDate = now()->subDays($daysInactive);
        $antiSpamDate = now()->subDays(30);

        $clients = \App\Models\Client::whereDoesntHave('bookings', function ($query) use ($thresholdDate) {
            $query->where('date', '>=', $thresholdDate->toDateString());
        })
        ->where(function ($query) use ($antiSpamDate) {
            $query->whereNull('last_reactivation_sent_at')
                  ->orWhere('last_reactivation_sent_at', '<', $antiSpamDate);
        })
        ->whereNotNull('email')
        ->get();

        foreach ($clients as $client) {
            $code = 'MISSYOU-' . strtoupper(\Illuminate\Support\Str::random(6));
            
            $voucher = \App\Models\Voucher::create([
                'code' => $code,
                'type' => 'percentage',
                'value' => $percent,
                'start_date' => now(),
                'end_date' => now()->addDays($validDays),
                'max_uses' => 1,
                'active' => true,
            ]);

            \App\Models\ClientVoucher::create([
                'client_id' => $client->id,
                'voucher_id' => $voucher->id,
            ]);

            // Notify via Email (TODO: Create Notification)
            // $client->notify(new \App\Notifications\ReactivationVoucherNotification($voucher, $percent, $validDays));

            $client->update(['last_reactivation_sent_at' => now()]);

            $this->info("Campanie trimisă către {$client->name} ({$client->email})");
        }
    }
}
