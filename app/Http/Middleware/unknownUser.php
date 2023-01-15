<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Models\User;
use App\Models\Role;
use App\Http\Traits\ResponseTrait;

class unknownUser
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      
        if(Session::has('user') && Session::get('user') !== null && Session::has('roleId')){
            $user = User::find(encryptor('decrypt', Session::get('user')));
            $role = Role::find(encryptor('decrypt', Session::get('roleId')));
            
            if (!!$user && $role->identity == 'superadmin') 
                return redirect(route('superadminDashboard'));
            else if (!!$user && $role->identity == 'owner')
                return redirect(route('ownerDashboard'));
            else if (!!$user && $role->identity == 'salesmanager')
                return redirect(route('salesmanagerDashboard'));
            else if (!!$user && $role->identity == 'salesman')
                return redirect(route('salesmanDashboard'));
            else if (!!$user && $role->identity == 'admin')
                return redirect(route('adminDashboard'));
            else if (!!$user && $role->identity == 'dataentry')
                return redirect(route('dataentryDashboard'));
            else if (!!$user && $role->identity == 'executive')
                return redirect(route('executiveDashboard'));
            else if (!!$user && $role->identity == 'accountmanager')
                return redirect(route('accountmanagerDashboard'));
            else if (!!$user && $role->identity == 'marketingmanager')
                return redirect(route('marketingmanagerDashboard'));
            else 
                return redirect(route('signInForm'))->with($this->responseMessage(false, "error", 'Log In faild'));
            
            return $next($request);
        }

        return $next($request);
    }
}
