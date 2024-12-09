<?php

namespace Database\Seeders;

use App\Models\OtherPaymentCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherPaymentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OtherPaymentCategory::factory()->count(2)->create();
    }
}
