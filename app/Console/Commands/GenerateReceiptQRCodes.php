<?php

namespace App\Console\Commands;

use App\Models\Receipt;
use Illuminate\Console\Command;

class GenerateReceiptQRCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:generate-qr-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR codes for existing receipts that don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $receipts = Receipt::where('qr_code', '')->with('transaction')->get();
        
        if ($receipts->isEmpty()) {
            $this->info('No receipts found without QR codes.');
            return;
        }

        $this->info("Generating QR codes for {$receipts->count()} receipts...");
        
        $bar = $this->output->createProgressBar($receipts->count());
        
        foreach ($receipts as $receipt) {
            $verificationUrl = route('staff.orders.verify', [
                'receipt_code' => $receipt->receipt_code,
                'transaction_id' => $receipt->transaction_id
            ]);
            
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(200)
                ->margin(2)
                ->generate($verificationUrl);
                
            $receipt->update([
                'qr_code' => 'data:image/svg+xml;base64,' . base64_encode((string) $qrCode)
            ]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('QR codes generated successfully!');
    }
}