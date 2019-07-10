<?php

namespace App\Providers;

use App\Repositories\CsvData\CsvDataInterface;
use App\Repositories\CsvData\CsvDataRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // CSV data repo
        $this->app->singleton(CsvDataInterface::class, CsvDataRepository::class);

        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
