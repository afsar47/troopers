<?php

namespace App\Providers;

//use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot() {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $key = explode(' ', $request->header('Authorization'));
                
                $user = DB::select('select * from member where api_token = "' . $key[1] . '"'); // change for php 8 

                // $user = DB::select('select * from member where api_token = "' . $key[1] . '"', [1])[0];
                if (!empty($user)) {
                    
                    $user = $user[0];

                    $request->request->add(['userid' => $user->member_id]);
                    $request->request->add(['password' => $user->password]);
               
                    return $user;
                }                
            }
        });

//        $this->app['auth']->viaRequest('api', function ($request) {
//            if ($request->header('Authorization')) {
//                $user = DB::select('select * from member where user_name = "' . $request->getUser() . '" and password = "' . md5($request->getPassword()) . '"', [1])[0];
//                if (!empty($user)) {
//                    $request->request->add(['userid' => $user->member_id]);
//                }
//                return $user;
//            }
//        });
    }

}
