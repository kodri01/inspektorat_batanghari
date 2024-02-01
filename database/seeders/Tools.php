<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class Tools extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Wilayah::query()->truncate();
        Wilayah::create(['name' => 'INSPEKTUR PEMBANTU 1']);
        Wilayah::create(['name' => 'INSPEKTUR PEMBANTU 2']);
        Wilayah::create(['name' => 'INSPEKTUR PEMBANTU 3']);
        Wilayah::create(['name' => 'INSPEKTUR PEMBANTU 4']);
        Wilayah::create(['name' => 'INSPEKTUR PEMBANTU KHUSUS']);
    }
}
