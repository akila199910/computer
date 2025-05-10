<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $action = ['Read', 'Create' , 'Update' , 'Delete'];
        // $permissions = ['Business','Business_User', 'User','Admin','Inventory','Inventory_Category','Inventory_Sub_Category','Asset','Employee','Asset_Handling','Report','Department','Category','Sub_Category'];

        // $insert_data = [];
        // foreach ($permissions as $key => $value) {
        //     foreach ($action as $act_key => $act_value) {
        //         $insert_data[] = [
        //             'name' => $act_value . "_" . $value,
        //             'guard_name' => 'web',
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ];
        //     }
        // }

        // DB::table('permissions')->insert($insert_data);
    }
}
