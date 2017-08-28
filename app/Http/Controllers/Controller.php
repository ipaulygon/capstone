<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;
use View;
use App\Utilities;
use App\User;
use App\Technician;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    private $id;
    private $user;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $util = Utilities::find(1);
            $this->id = Auth::id();
            $this->user = User::find($this->id);
            if($this->user->type==1){
                $wholeName = 'Administrator';
                $userName = 'Admin';
                $userPicture = $util->image;
            }else{
                $id = str_replace('TECH-','',$this->user->name);
                $id = (int)$id;
                $techUser = Technician::find($id);
                $wholeName = $techUser->firstName.' '.$techUser->middleName.' '.$techUser->lastName;
                $userName = $this->user->name;
                $userPicture = $techUser->image;
                View::share('techUser', $techUser);
            }
            View::share('user', $this->user);
            View::share('wholeName', $wholeName);
            View::share('userName', $userName);
            View::share('userPicture', $userPicture);
            View::share('util', $util);
            return $next($request);
        });        
    }  
}
