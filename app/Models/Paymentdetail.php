<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentdetail extends Model
{
    use HasFactory;
    public function payment()
    {
        return $this->hasOne(Payment::class,'id','paymentId');
    }
    public function batch()
    {
        return $this->hasOne(Batch::class,'id','batchId');
    }
    public function student(){
        return $this->hasOne(Student::class,'id','studentId');
    }
}