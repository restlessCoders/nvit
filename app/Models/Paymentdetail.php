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
    public function course()
    {
        return $this->hasOne(Course::class,'id','course_id');
    }
    public function student(){
        return $this->hasOne(Student::class,'id','studentId');
    }
        // add an accessor for cPayableAmount
        public function getCPayableAmountAttribute()
        {
            return $this->attributes['cPayableAmount'];
        }
    
        // add a mutator for PaidAmount
        public function setPaidAmountAttribute($value)
        {
            $this->attributes['PaidAmount'] = $value;
        }
}
