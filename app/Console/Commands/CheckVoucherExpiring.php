<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckVoucherExpiring extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-voucher-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifică și marchează voucherele expirate ca inactive.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        // 1. Regular Vouchers
        $expiredVouchers = \App\Models\Voucher::where('active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', $today)
            ->update(['active' => false]);

        // 2. Gift Vouchers
        $expiredGiftVouchers = \App\Models\GiftVoucher::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $today)
            ->update(['status' => 'expired']);

        $this->info("Procesat: {$expiredVouchers} vouchere normale și {$expiredGiftVouchers} carduri cadou expirate.");
    }
}
