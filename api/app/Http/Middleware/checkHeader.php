<?php

namespace App\Http\Middleware;  
  
use Closure;  
use Illuminate\Contracts\Auth\Guard;  
use Response;  
  
class checkHeader  
{  
    /** 
     * The Guard implementation. 
     * 
     * @var Guard 
     */  
  
    /** 
     * Handle an incoming request. 
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @param  \Closure  $next 
     * @return mixed 
     */  
    public function handle($request, Closure $next)  
    {  
         $request->headers->set('Access-Control-Allow-Headers', 'Content-Type, Content-Range, Content-Disposition, Content-Description');
         $request->headers->set('Pragma', 'no-cache');
         $request->headers->set('Cache-Control', 'no-cache');
         $request->headers->set('Expires', '0');
//         header("Pragma: no-cache");
//        header("Cache-Control: no-cache");
//        header("Expires: 0");
//        if(!isset($_SERVER['HTTP_X_HARDIK'])){  
//            return Response::json(array('error'=>'Please set custom header'));  
//        }  
//  
//        if($_SERVER['HTTP_X_HARDIK'] != '123456'){  
//            return Response::json(array('error'=>'wrong custom header'));  
//        }  
  
        return $next($request);  
    }  
}