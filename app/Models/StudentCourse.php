<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'course_id',
        'semester_tenure_id',
        'section_id',
        'status',
        'dis_amount',
        'enrolled_at',
        'created_by',
        'updated_by'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function semesterTenure()
    {
        return $this->belongsTo(SemesterTenure::class);
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class, 'course_id'); // course_id refers to student_courses.id
    }
}
