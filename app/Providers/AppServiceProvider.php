<?php

namespace App\Providers;

use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            View::share('rooms', Room::with('user_1')->get());
        } catch (\Exception $e) {}
    }
}
