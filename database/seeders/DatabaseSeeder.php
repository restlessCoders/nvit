<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\District;
use App\Models\Division;
use App\Models\Reference;
use App\Models\Upazila;
use App\Models\Batchtime;
use App\Models\Batchslot;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Student;
use App\Models\UserDetail;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        UserDetail::factory(11)->create();
       
        //Time Slot
        foreach(time_slots() as $tslot) {
            $batchtimeModel = new Batchtime();
            $batchtimeModel->time = $tslot['time'];
            $batchtimeModel->status = $tslot['status'];
            $batchtimeModel->save();
        }

        //Batch Weekday Slot
        foreach(batch_slots() as $bwslot) {
            $batchwModel = new Batchslot();
            $batchwModel->slotName = $bwslot['slotName'];
            $batchwModel->status = $bwslot['status'];
            $batchwModel->save();
        }

        //Clasroom
        foreach(classrooms() as $classroom) {
            $classroomModel = new Classroom();
            $classroomModel->classroom = $classroom['classroom'];
            $classroomModel->pc_seat = $classroom['pc_seat'];
            $classroomModel->no_pc_seat = $classroom['no_pc_seat'];
            $classroomModel->status = $classroom['status'];
            $classroomModel->save();
        }
        //Clasroom
        foreach(courses() as $course) {
            $courseModel = new Course();
            $courseModel->courseName = $course['courseName'];
            $courseModel->status = $course['status'];
            $courseModel->save();
        }
        

        // creating divisions
        foreach(tika_bd_divisions() as $division) {
            $divisionModel = new Division();
            $divisionModel->name = $division['name'];
            $divisionModel->save();
        }


        // creating districts
        foreach(tika_bd_districts() as $district) {
            $districtModel = new District();
            $districtModel->name = $district['name'];
            $districtModel->division_id = $district['division_id'];
            $districtModel->save();
        }

        // creating references
        foreach(references() as $reference) {
            $referenceModel = new Reference();
            $referenceModel->refName = $reference['refName'];
            $referenceModel->status = $reference['status'];
            $referenceModel->save();
        }

        // creating upazilas
        foreach(tika_bd_upazilas() as $upazila) {
            $upazilaModel = new Upazila();
            $upazilaModel->name = $upazila['name'];
            $upazilaModel->district_id = $upazila['district_id'];
            $upazilaModel->save();
        }
        Student::factory(50)->create();
        /*Student::factory(50)->create()->each(function ($student) {
            $student->courses()->attach(
                Course::inRandomOrder()->first()->id,['status' => rand(1,5)]
            );
        });*/

    }
}
