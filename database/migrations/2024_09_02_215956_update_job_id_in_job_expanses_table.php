<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('job_expanses')
            ->join('appointments', 'job_expanses.job_id', '=', 'appointments.id')
            ->update([
                'job_expanses.job_id' => DB::raw('appointments.job_id')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('job_expanses')
        ->join('appointments', 'job_expanses.job_id', '=', 'appointments.job_id')
        ->update([
            'job_expanses.job_id' => DB::raw('appointments.id')
        ]);
    }
};