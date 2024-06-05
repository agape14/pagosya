<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InsertYearIfNotExists extends Command
{
    protected $signature = 'insert:year-if-not-exists';
    protected $description = 'Insert the current year and the next year if they do not exist in the anios table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;

        $years = [$currentYear, $nextYear];

        foreach ($years as $year) {
            $exists = DB::table('anios')->where('anio', $year)->exists();

            if (!$exists) {
                DB::table('anios')->insert(['anio' => $year, 'created_at' => now(), 'updated_at' => now()]);
                $this->info("Year $year inserted successfully.");
            } else {
                $this->info("Year $year already exists.");
            }
        }
    }
}
