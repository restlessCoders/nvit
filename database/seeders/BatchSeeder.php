<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Batch;
use DB;
class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * batchId`, `courseId`, `startDate`, `endDate`, `bslot`, `btime`, `trainerId`, `examDate`, `examTime`, `examRoom`, `seat`, `courseDuration`, `classHour`, `totalClass`, `remarks`, `status`, `type`
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Batch::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $csvFile = fopen(base_path("database/data/batches.csv"), "r");
        $batches = array();
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            Batch::create(array(
                "id" => trim($data['0']),
                "batchId" => trim($data['1']),
                "courseId" => trim($data['2']),
                "startDate" => trim($data['3']),
                "endDate" => trim($data['4']),
                "bslot" => trim($data['5']),
                "btime" => trim($data['6']),
                "trainerId" => trim($data['7']),
                "examDate" => trim($data['8']),
                "examTime" => trim($data['9']),
                "examRoom" =>trim($data['10']),
                "seat" => trim($data['11']),
                "courseDuration" => trim($data['12']),
                "classHour" =>trim($data['13']),
                "totalClass" => trim($data['14']),
                "remarks" => trim($data['15']),
                "status" => trim($data['16']),
                "type" => trim($data['17'])
            ));
        }

        fclose($csvFile);
    }
}
