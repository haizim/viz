<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app('laravolt.menu.sidebar')->register(function (\Lavary\Menu\Builder $menu) {
           $groupManage = $menu->add('Manage');
            $menus = [
                'databases' => ['Databases', 'database'],
                'queries' => ['Queries', 'code'],
                'dashboard' => ['Dashboard', 'columns']];

            foreach ($menus as $addr => $val) {
                $groupManage->add($val[0], $addr)
                ->data('icon', $val[1])
                ->data('order', 10)
                // ->data('permission', 'foo')
                ->active("manage/*");
            }
        });
    }
}
