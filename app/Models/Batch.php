<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    public function course(){
        return $this->belongsTo(Course::class,'courseId','id');
    }
    public function batchslot(){
        return $this->belongsTo(Batchslot::class,'bslot','id');
    }
    public function batchtime(){
        return $this->belongsTo(Batchtime::class,'btime','id');
    }
    public function trainer(){
        return $this->belongsTo(User::class,'trainerId','id');
    }
    public function createdby(){
        return $this->belongsTo(User::class,'userId','id');
    }
}
