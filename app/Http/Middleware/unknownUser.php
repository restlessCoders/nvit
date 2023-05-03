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

        if (Session::has('user') && Session::get('user') !== null && Session::has('roleId')) {
            $user = User::find(encryptor('decrypt', Session::get('user')));
            $role = Role::find(encryptor('decrypt', Session::get('roleId')));

            if (!!$user && $role->identity == 'superadmin')
                return redirect(route('superadminDashboard'));
            else if (!!$user && $role->identity == 'operationmanager')
                return redirect(route('operationmanagerDashboard'));
            else if (!!$user && $role->identity == 'salesexecutive')
                return redirect(route('salesexecutiveDashboard'));
            else if (!!$user && $role->identity == 'salesmanager')
                return redirect(route('salesmanagerDashboard'));
            else if (!!$user && $role->identity == 'accountmanager')
                return redirect(route('accountmanagerDashboard'));
            else if (!!$user && $role->identity == 'trainer')
                return redirect(route('trainerDashboard'));
            else if (!!$user && $role->identity == 'frontdesk')
                return redirect(route('frontdeskDashboard'));
            else
                return redirect(route('signInForm'))->with($this->responseMessage(false, "error", 'Log In faild'));

            return $next($request);
        }

        return $next($request);
    }
}
