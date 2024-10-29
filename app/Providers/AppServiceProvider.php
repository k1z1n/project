<?php

namespace App\Providers;

use App\Models\Database;
use App\Models\FileZilla;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::composer('*', function ($view) {
            $fileZilla = FileZilla::where('user_id', auth()->id())->first();
            $database = Database::where('user_id', auth()->id())->first();
            $view->with('fileZilla', $fileZilla);
            $view->with('database', $database);
        });

        // Используем Tailwind CSS для пагинации
        Paginator::useTailwind();

        // Определение кастомных Blade-директив
        Blade::if('adminArea', function () {
            return request()->is('admin') || request()->is('admin/*');
        });

        Blade::if('studentArea', function () {
            return request()->is('student') || request()->is('student/*');
        });

        Blade::if('teacherArea', function () {
            return request()->is('teacher') || request()->is('teacher/*');
        });
    }
}
