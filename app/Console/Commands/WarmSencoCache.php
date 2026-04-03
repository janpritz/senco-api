<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Admin\CollectionController;

#[Signature('app:warm-senco-cache')]
#[Description('Command description')]
class WarmSencoCache extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        Cache::forget('senco_masterlist');
        app(CollectionController::class)->index(request());
    }
}
