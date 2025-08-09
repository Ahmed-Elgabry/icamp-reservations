<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;

class crud extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name=name} {SingleName=SingleName} {PluraleName=PluraleName} {--ob} {--seed} {--request} {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {

        $model          = $this->argument('name');
        $SingleName     = $this->argument('SingleName');
        $PluraleName    = $this->argument('PluraleName');
        if ($this->confirm('sure you want to continue with name ' . $model, true)) {
            $foldername = strtolower(Str::plural(class_basename($model)));
            $singleName = strtolower(class_basename($model));

            // Start Creating model with mogration and model content
            Artisan::call('make:model', ['name' => $model, '-m' => true]);
            File::copy('app/Models/copy.php', base_path('app/Models/' . $model . '.php'));
            file_put_contents('app/Models/' . $model . '.php', preg_replace("/Copy/", $model, file_get_contents('app/Models/' . $model . '.php')));
            file_put_contents('app/Models/' . $model . '.php', preg_replace("/imagesfolder/", $foldername, file_get_contents('app/Models/' . $model . '.php')));
            // End model with mogration and model content

            // create Controller
            Artisan::call('make:controller', ['name' => 'Dashboard/' . $model . 'Controller']);
         
            File::copy('app/Http/Controllers/Dashboard/CopyController.php', base_path('app/Http/Controllers/Dashboard/' . $model . 'Controller.php'));
            
            file_put_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php', preg_replace("/copys/", $foldername, file_get_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php')));
            file_put_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php', preg_replace("/copy/", $singleName, file_get_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php')));
            file_put_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php', preg_replace("/Copy/", $model, file_get_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php')));
            file_put_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php', preg_replace("/SingleName/", $SingleName, file_get_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php')));
            file_put_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php', preg_replace("/PluraleName/", $PluraleName, file_get_contents('app/Http/Controllers/Dashboard/' . $model . 'Controller.php')));
            // #create Controller


            
            // create Repository 
            File::copy('app/Repositories/Eloquent/CopyRepository.php',base_path('app/Repositories/Eloquent/'.$model.'Repository.php'));
            file_put_contents('app/Repositories/Eloquent/'.$model.'Repository.php', preg_replace("/CopyRepository/", $model.'Repository', file_get_contents('app/Repositories/Eloquent/'.$model.'Repository.php')));
            file_put_contents('app/Repositories/Eloquent/'.$model.'Repository.php', preg_replace("/ICopy/", 'I'.$model , file_get_contents('app/Repositories/Eloquent/'.$model.'Repository.php')));
            file_put_contents('app/Repositories/Eloquent/'.$model.'Repository.php', preg_replace("/Copy/", $model , file_get_contents('app/Repositories/Eloquent/'.$model.'Repository.php')));
            // create Repository 
        
            // create interface 
            File::copy('app/Repositories/ICopyRepository.php',base_path('app/Repositories/I'.$model.'Repository.php'));
            file_put_contents('app/Repositories/I'.$model.'Repository.php', preg_replace("/ICopy/", 'I'.$model , file_get_contents('app/Repositories/I'.$model.'Repository.php')));
            // #create interface 



            // create observer (optional) 
            if ($this->option('ob')) {
                Artisan::call('make:observer', ['name' => $model.'Observer']);
                File::copy('app/Observers/CopyObserver.php',base_path('app/Observers/'.$model.'Observer.php'));
                file_put_contents('app/Observers/'.$model.'Observer.php', preg_replace("/CopyObserver/", $model.'Observer', file_get_contents('app/Observers/'.$model.'Observer.php')));
                file_put_contents('app/Observers/'.$model.'Observer.php', preg_replace("/Copy/", $model , file_get_contents('app/Observers/'.$model.'Observer.php')));
                file_put_contents('app/Observers/'.$model.'Observer.php', preg_replace("/coyps/", $foldername , file_get_contents('app/Observers/'.$model.'Observer.php')));
            }
             // #create observer (optional) 



            // create folder and blade file
            // make directory folder
            //make repository ICopyRepository
            File::makeDirectory('resources/views/dashboard/' . $foldername);
            // create index page //
          
            File::copy('resources/views/dashboard/copys/index.blade.php', base_path('resources/views/dashboard/' . $foldername . '/index.blade.php'));
            
            file_put_contents(
                'resources/views/dashboard/' . $foldername . '/index.blade.php'
                , preg_replace(
                    "/copys/"
                    , $foldername,
                    file_get_contents('resources/views/dashboard/' . $foldername . '/index.blade.php')
                )
            );
            file_put_contents(
                'resources/views/dashboard/' . $foldername . '/index.blade.php'
                ,
                preg_replace(
                    "/copy/"
                    , $SingleName,
                    file_get_contents('resources/views/dashboard/' . $foldername . '/index.blade.php')
                )
            );
            // create index page 

            // create create form page 
  
            File::copy('resources/views/dashboard/copys/create.blade.php', base_path('resources/views/dashboard/' . $foldername . '/create.blade.php'));
            

            file_put_contents(
                'resources/views/dashboard/' . $foldername . '/create.blade.php'
                , preg_replace(
                    "/copys/"
                    , $foldername,
                    file_get_contents('resources/views/dashboard/' . $foldername . '/create.blade.php')
                )
            );

            file_put_contents(
                'resources/views/dashboard/' . $foldername . '/create.blade.php'
                , preg_replace(
                    "/copy/"
                    , $SingleName,
                    file_get_contents('resources/views/dashboard/' . $foldername . '/create.blade.php')
                )
            );
            // #create create form page

            // create edit form page 
          
            File::copy('resources/views/dashboard/copys/edit.blade.php', base_path('resources/views/dashboard/' . $foldername . '/edit.blade.php'));
            

            file_put_contents(
                'resources/views/dashboard/' . $foldername . '/edit.blade.php'
                , preg_replace(
                    "/copys/"
                    , $foldername,
                    file_get_contents('resources/views/dashboard/' . $foldername . '/edit.blade.php')
                )
            );

            file_put_contents(
                'resources/views/dashboard/' . $foldername . '/edit.blade.php'
                , preg_replace(
                    "/copy/"
                    , $singleName,
                    file_get_contents('resources/views/dashboard/' . $foldername . '/edit.blade.php')
                )
            );

            file_put_contents(
                'resources/views/dashboard/' . $foldername . '/edit.blade.php'
                , preg_replace(
                    "/copy/"
                    , $SingleName,
                    file_get_contents('resources/views/dashboard/' . $foldername . '/edit.blade.php')
                )
            );
            // create edit form page //

            // create show page //
            // File::copy('resources/views/dashboard/copys/show.blade.php', base_path('resources/views/dashboard/' . $foldername . '/show.blade.php'));
            
            // file_put_contents(
            //     'resources/views/dashboard/' . $foldername . '/show.blade.php'
            //     , preg_replace(
            //         "/copy/"
            //         , $SingleName,
            //         file_get_contents('resources/views/dashboard/' . $foldername . '/show.blade.php')
            //     )
            // );
            // create show page //

            // #create folder and blade file

            // create web routes
            file_put_contents('routes/web.php',
                preg_replace(
                    "/#new_routes_here/",
                    "
    /*------------ start Of " . $foldername . " ----------*/
        Route::get('" . $foldername . "', [
            'uses'      => '" . $model . "Controller@index',
            'as'        => '" . $foldername . ".index',
            'title'     => '" . $PluraleName . "',
            'type'      => 'parent',
            'sub_route' => false,
            'child'     => ['" . $foldername . ".create', '" . $foldername . ".store','" . $foldername . ".edit', '" . $foldername . ".update', '" . $foldername . ".show', '" . $foldername . ".destroy'  ,'" . $foldername . ".deleteAll' ,]
        ]);

        # " . $foldername . " store
        Route::get('" . $foldername . "/create', [
            'uses'  => '" . $model . "Controller@create',
            'as'    => '" . $foldername . ".create',
            'title' => ['actions.add', 'dashboard.".$SingleName."']
        ]);


        # " . $foldername . " store
        Route::post('" . $foldername . "/store', [
            'uses'  => '" . $model . "Controller@store',
            'as'    => '" . $foldername . ".store',
            'title' => ['actions.add', 'dashboard.".$SingleName."']

        ]);

        # " . $foldername . " update
        Route::get('" . $foldername . "/{id}/edit', [
            'uses'  => '" . $model . "Controller@edit',
            'as'    => '" . $foldername . ".edit',
            'title' => ['actions.edit','dashboard.".$SingleName."']
        ]);

        # " . $foldername . " update
        Route::put('" . $foldername . "/{id}', [
            'uses'  => '" . $model . "Controller@update',
            'as'    => '" . $foldername . ".update',
            'title' => ['actions.edit', 'dashboard.".$SingleName."']
        ]);

        # " . $foldername . " show
        Route::get('" . $foldername . "/{id}/Show', [
            'uses'  => '" . $model . "Controller@show',
            'as'    => '" . $foldername . ".show',
            'title' => ['actions.show','dashboard.".$SingleName."']
        ]);

        # " . $foldername . " delete
        Route::delete('" . $foldername . "/{id}', [
            'uses'  => '" . $model . "Controller@destroy',
            'as'    => '" . $foldername . ".delete',
            'title' => ['actions.delete', 'dashboard.".$SingleName."']
        ]);
        #delete all " . $foldername . "
        Route::post('delete-all-" . $foldername . "', [
            'uses'  => '" . $model . "Controller@deleteAll',
            'as'    => '" . $foldername . ".deleteAll',
            'title' => ['actions.delete_all', 'dashboard.".$PluraleName."']
        ]);
    /*------------ end Of " . $foldername . " ----------*/
    #new_routes_here
                     ",
                    file_get_contents('routes/web.php')
                ));

            Artisan::call('route:clear');
            // #create web wroutes

            // create seeder (optional)
            if ($this->option('seed')) {
                Artisan::call('make:seeder', ['name' => $model . 'TableSeeder']);
            }
            // #create seeder (optional)

            // create request (optional)
            if ($this->option('request')) {

                File::copy('app/Requests/dashboard/CreateUpdateCopyRequest.php', base_path('app/Requests/dashboard/CreateUpdate' . $model . 'Request.php'));
                file_put_contents('app/Requests/dashboard/CreateUpdate' . $model . 'Request.php', preg_replace("/Copy/", $model, file_get_contents('app/Requests/dashboard/CreateUpdate' . $model . 'Request.php')));

            }
            // #create request (optional)

            // create request (optional)
            if ($this->option('resource')) {
                Artisan::call('make:resource', ['name' => 'Api/' . $model . 'Resource']);
            }
            // #create request (optional)

            // call back
            $this->info('New Repository , Interface , Dashboard Controller , Model , DataBase Migrate , optional commands [ database seeder , admin section form request , observer] , Blade Folder And Blade File on dashboard , basic [index - store - update - delete] routes in web.php file for dashboard are created successfully ! ');
            // #call back
        }
    }
}
