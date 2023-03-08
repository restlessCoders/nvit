<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    public function course(){
        return $this->belongsTo(Course::class,'courseId','id');
    }
    public function batch(){
        return $this->belongsTo(Batch::class,'batchId','id');
    }
}
