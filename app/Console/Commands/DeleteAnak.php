<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StatistikAnak;
use App\Models\Anak;
use Carbon\Carbon;

class DeleteAnak extends Command
{
        /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    protected $signature = 'anak:delete';
    protected $description = 'Delete anak yang umurnya 59 bulan keatas.';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fiveNineMonthsAgo = Carbon::now()->subMonths(59)->format('Y-m-d');

        $anakToDelete = Anak::whereDate('tanggal_lahir', '<=', $fiveNineMonthsAgo)->get();
        foreach ($anakToDelete as $anak) {
            StatistikAnak::where('id_anak', $anak->id)->delete();
            $anak->delete();
        }

        $this->info('Users deleted successfully.');
    }
}
