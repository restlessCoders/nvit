<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Role;
use App\Models\UserDetail;

class User extends Model
{
    public function role(){
        return $this->belongsTo(Role::class, 'roleId');
    }
    public function details(){
        return $this->hasOne(UserDetail::class,'userId');
    }
    
    public function company(){
        return $this->belongsTo(Company::class,'companyId','companyId');
    }
    
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
