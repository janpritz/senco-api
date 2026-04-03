<?php
use Illuminate\Support\Facades\Schedule;
use App\Services\Admin\GoogleSheetSyncService;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

// Run the sync every 5 minutes
Schedule::call(function () {
    (new GoogleSheetSyncService())->syncMasterlist();
})->everyFiveMinutes()->name('sync-google-sheets')->withoutOverlapping();
