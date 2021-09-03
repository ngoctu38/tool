<?php

namespace Database\Seeders;

use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admin_roles')->insert([
            'name' => 'Manager',
            'slug' => 'manager'
        ]);
        DB::table('admin_roles')->insert([
            'name' => 'Project Manager',
            'slug' => 'pm'
        ]);
    }
}
