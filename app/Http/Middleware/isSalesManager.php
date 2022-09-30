<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use App\Models\User;
use App\Http\Traits\ResponseTrait;
use Session;

class isSalesManager
{
     use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
		if(!Session::has('user') || Session::get('user') == null || !Session::has('roleId')){
            return redirect()->route('logOut');
        }else{
            $user = User::find(encryptor('decrypt', Session::get('user')));
            $role = Role::find(encryptor('decrypt', Session::get('roleId')));
            if(!$user || !$role){
                return redirect()->route('logOut');
            }else if ($role->identity != 'salesmanager') {
                return redirect(route('salesmanagerDashboard'))->with($this->responseMessage(true, null, 'Access Deined'));
            }else{
                return $next($request);
            }
        }
        return redirect()->route('logOut');
    }
}
