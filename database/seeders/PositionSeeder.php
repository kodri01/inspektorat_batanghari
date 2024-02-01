<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permissions = [
            '1' => ['dashboards'],
            '2' =>  [
                'users-list',
                'users-create',
                'users-edit',
                'users-delete'
            ],
            '3' =>  [
                'temuan-list',
                'temuan-create',
                'temuan-edit',
                'temuan-delete'
            ],
            '4' =>  [
                'tindakan-list',
                'tindakan-create',
                'tindakan-edit',
                'tindakan-delete'
            ],
            '5' =>  [
                'laporan-list',
                'laporan-create',
                'laporan-edit',
                'laporan-delete'
            ],
        ];

        foreach ($permissions as $permission => $values) {
            foreach ($values as $value) {
                Permission::create(['parent' => $permission, 'name' => $value]);
            }
        }
    }
}