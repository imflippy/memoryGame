<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Broadcast::routes(['middleware' => ['jwt.verify']]); //if you use JWT

        require base_path('routes/channels.php');
    }
}
