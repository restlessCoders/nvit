<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Student extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'contact'];
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }
    public function reference()
    {
        return $this->belongsTo(Reference::class,'refId','id');
    }
    public function courses(){
        return $this->belongsToMany(Course::class,'student_courses','student_id','course_id')->withPivot('status')->withTimestamps();;
    }
}
