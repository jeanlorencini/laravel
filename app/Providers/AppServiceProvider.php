<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; // Adicione esta linha

class AppServiceProvider extends ServiceProvider
{
    // ...

    public function boot()
    {
        Blade::componentNamespace('Flowbite\\Components', 'form.input');
    }

    // ...
}
