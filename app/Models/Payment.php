<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    /*protected $fillable = ['created_by'];*/
    public function studentData(){
        return $this->belongsTo(Student::class,'studentId','id');
    }
    public function executiveData(){
        return $this->belongsTo(User::class,'executiveId','id');
    }
    public function postedData(){
        return $this->belongsTo(user::class,'createdBy','id');
    }
    public function paymentDetail()
    {
        return $this->hasMany(Paymentdetail::class,'paymentId','id');
    }
    public function user(){
        return $this->hasOne(User::class,'id','executiveId');
    }
}
