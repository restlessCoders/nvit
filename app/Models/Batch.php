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
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function students(){
        //return $this->belongsToMany(Batch::class, 'student_batches', 'batch_id');
        return $this->belongsToMany('student_batches', 'batch_id', 'id')
        ->selectRaw('sum(student_batches.student_id) as tst')
        ->groupBy('student_batches.batch_id');
    }
    public function studentsBatches()
    {
        return $this->belongsToMany(Student::class, 'student_batches')->select(['course_price','entryDate']);
    }
    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class, 'batch_id', 'id');
    }
}
