<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendBirthdayVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-birthday-vouchers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trimite automat vouchere de zi de naștere clienților.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enabled = \App\Models\Setting::getValue('birthday_voucher_enabled', '0');
        if ($enabled != '1') {
            $this->info('Voucherele de zi de naștere sunt dezactivate.');
            return;
        }

        $percent = \App\Models\Setting::getValue('birthday_voucher_percent', '20');
        $validDays = (int) \App\Models\Setting::getValue('birthday_voucher_valid_days', '14');
        $businessName = \App\Models\Setting::getValue('business_name', 'Salonul Nostru');

        $today = now();
        $clients = \App\Models\Client::whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', $today->day)
            ->get();

        foreach ($clients as $client) {
            $code = 'BDAY-' . strtoupper(\Illuminate\Support\Str::random(6));
            
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

            // SMS Notification
            $smsMessage = "La mulți ani {$client->name}! {$businessName} îți oferă un voucher cadou de {$percent}%. Cod: {$code}. Valabil {$validDays} zile.";
            \App\Services\SmsService::queue($client->phone, $smsMessage, 'engagement', null, 'birthday');

            // Email Notification (TODO: Create Mailable/Notification)
            // $client->notify(new \App\Notifications\BirthdayVoucherNotification($voucher, $percent, $validDays));

            $this->info("Voucher trimis către {$client->name} ({$client->phone})");
        }
    }
}
