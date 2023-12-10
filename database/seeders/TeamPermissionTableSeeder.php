<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\Role;

class TeamPermissionTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [ 
            [
                'id'    => 40,
                'title' => 'team_create',
            ],
            [
                'id'    => 41,
                'title' => 'team_edit',
            ],
            [
                'id'    => 42,
                'title' => 'team_show',
            ],
            [
                'id'    => 43,
                'title' => 'team_delete',
            ],
            [
                'id'    => 44,
                'title' => 'team_access',
            ], 
        ];

        Permission::insert($permissions);

        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_' && substr($permission->title, 0, 5) != 'role_' && substr($permission->title, 0, 11) != 'permission_';
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);



    }
}
