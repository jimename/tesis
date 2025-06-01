<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=User::create([
            'name'=>'admin',
            'email'=>'admin@test.com',
            'username'=>'admin',
            'password'=>bcrypt('admin')
        ]);
//        Permission::create(['name'=>'empresa create']);
//        Permission::create(['name'=>'empresa read']);
//        Permission::create(['name'=>'empresa update']);
//        Permission::create(['name'=>'empresa delete']);
//
//        Permission::create(['name'=>'contratista create']);
//        Permission::create(['name'=>'contratista read']);
//        Permission::create(['name'=>'contratista update']);
//        Permission::create(['name'=>'contratista delete']);
//
//        Permission::create(['name'=>'transporte create']);
//        Permission::create(['name'=>'transporte read']);
//        Permission::create(['name'=>'transporte update']);
//        Permission::create(['name'=>'transporte delete']);
//
//        Permission::create(['name'=>'tornaguia create']);
//        Permission::create(['name'=>'tornaguia read']);
//        Permission::create(['name'=>'tornaguia update']);
//        Permission::create(['name'=>'tornaguia delete']);
//
//        Permission::create(['name'=>'conductor create']);
//        Permission::create(['name'=>'conductor read']);
//        Permission::create(['name'=>'conductor update']);
//        Permission::create(['name'=>'conductor delete']);
//
//        Permission::create(['name'=>'user create']);
//        Permission::create(['name'=>'user read']);
//        Permission::create(['name'=>'user update']);
//        Permission::create(['name'=>'user delete']);
//
//        $user->givePermissionTo(
//            'empresa create',
//            'empresa read',
//            'empresa update',
//            'empresa delete',
//            'contratista create',
//            'contratista read',
//            'contratista update',
//            'contratista delete',
//            'transporte create',
//            'transporte read',
//            'transporte update',
//            'transporte delete',
//            'tornaguia create',
//            'tornaguia read',
//            'tornaguia update',
//            'tornaguia delete',
//            'conductor create',
//            'conductor read',
//            'conductor update',
//            'conductor delete',
//            'user create',
//            'user read',
//            'user update',
//            'user delete',
//        );
        Permission::create(['name'=>'Usuarios']);
        Permission::create(['name'=>'Trasporte']);
        Permission::create(['name'=>'Chofer']);


//        $user->assignRole('admin');
    }
}
