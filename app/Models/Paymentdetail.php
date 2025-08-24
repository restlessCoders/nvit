<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paymentdetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'payment_id',
        'student_id',
        'course_id',
        'fee_type_id',
        'month',
        'year',
        'amount'
    ];
    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'paymentId');
    }
    public function enrollment()
    {
        return $this->belongsTo(StudentCourse::class, 'course_id'); // course_id references student_courses
    }
    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'studentId');
    }
    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
    public function course()
    {
        // Adjust this based on your actual relationship
        return $this->belongsTo(Course::class, 'courseId'); // or whatever your foreign key is
    }
    public function executive()
    {
        // This might need to be through the payment relationship
        return $this->belongsTo(Student::class, 'executiveId');
    }
}
