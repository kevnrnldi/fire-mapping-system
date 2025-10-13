<?php

namespace App\Console\Commands;

use App\Models\GuestReport;
use Illuminate\Console\Command;

class PruneRejectedReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-rejected-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete rejected fire reports older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Pruning rejected reports...');

        $deletedCount = GuestReport::where('report_status', 'Ditolak')
                                ->where('updated_at', '<=', now()->subDay())
                                ->delete();

        $this->info("Done. Deleted {$deletedCount} rejected reports.");
        return 0;
    }
}
