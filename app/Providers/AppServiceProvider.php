<?php

namespace Corp\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //@set($i,10)
        Blade::directive('set', function ($exp) {

            list($name, $val) = explode(',', $exp);
            return "<?php $name = $val ?>";
        });

        //Чтобы избавиться от нежелательных sql запросы
        //для просмотра всех запросов на той/иной странице
        DB::listen(function ($query) {
         //   echo '<h1>'.$query->sql.'<h1>';

        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
