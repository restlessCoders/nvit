<?php

namespace Database\Seeders;

use App\Models\OtherPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OtherPayment::factory()->count(20)->create();
    }
}
