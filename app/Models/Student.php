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
    public function batches(){
        return $this->belongsToMany(Student::class,'student_batches','student_id','batch_id')->withPivot(['accountsNote','acc_approve','status']);;
    }
    public function executive(){
        return $this->belongsTo(User::class,'executiveId','id');
    }
    public function notes(){
        return $this->hasMany(Note::class,'student_id','id');
    }

}
