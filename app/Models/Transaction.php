<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public function user(){
        return $this->hasOne(User::class,'id','exe_id');
    }
    public function course(){
        return $this->hasOne(Course::class,'id','course_id');
    }
    public function batch(){
        return $this->hasOne(Batch::class,'id','batchId');
    }
}
