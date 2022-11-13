<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Schema::disableForeignKeyConstraints();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $permissions = [
            [
                'name' => 'create',
                'description' => 'The user can create organization',
                'group' => 'organization'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit organization',
                'group' => 'organization'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view organization',
                'group' => 'organization'
            ],
            [
                'name' => 'upgrade',
                'description' => 'The user can upgrade organization plan',
                'group' => 'organization'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete organization',
                'group' => 'organization'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create subsidiary',
                'group' => 'subsidiary'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit subsidiary',
                'group' => 'subsidiary'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view subsidiary',
                'group' => 'subsidiary'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete subsidiary',
                'group' => 'subsidiary'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create branch',
                'group' => 'branch'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit branch',
                'group' => 'branch'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view branch',
                'group' => 'branch'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete branch',
                'group' => 'branch'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create department',
                'group' => 'department'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit department',
                'group' => 'department'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view department',
                'group' => 'department'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete department',
                'group' => 'department'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create users',
                'group' => 'users'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit users',
                'group' => 'users'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view users',
                'group' => 'users'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete users',
                'group' => 'users'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create expenditure entry',
                'group' => 'expenditure entry'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit expenditure entry',
                'group' => 'expenditure entry'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view expenditure entry',
                'group' => 'expenditure entry'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete expenditure entry',
                'group' => 'expenditure entry'
            ],
            [
                'name' => 'approve',
                'description' => 'The user can approve expenditure entry',
                'group' => 'expenditure entry'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create expense limit',
                'group' => 'expense limit'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit expense limit',
                'group' => 'expense limit'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view expense limit',
                'group' => 'expense limit'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete expense limit',
                'group' => 'expense limit'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create revenue entry',
                'group' => 'revenue entry'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit revenue entry',
                'group' => 'revenue entry'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view revenue entry',
                'group' => 'revenue entry'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete revenue entry',
                'group' => 'revenue entry'
            ],
            [
                'name' => 'approve',
                'description' => 'The user can approve revenue entry',
                'group' => 'revenue entry'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create budget',
                'group' => 'budget'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit budget',
                'group' => 'budget'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view budget',
                'group' => 'budget'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete budget',
                'group' => 'budget'
            ],
            [
                'name' => 'approve',
                'description' => 'The user can approve budget',
                'group' => 'budget'
            ],
            [
                'name' => 'create',
                'description' => 'The user can create budget entry',
                'group' => 'budget entry'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit budget entry',
                'group' => 'budget entry'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view budget entry',
                'group' => 'budget entry'
            ],
            [
                'name' => 'delete',
                'description' => 'The user can delete budget entry',
                'group' => 'budget entry'
            ],
            [
                'name' => 'approve',
                'description' => 'The user can approve budget entry',
                'group' => 'budget entry'
            ],
            [
                'name' => 'edit',
                'description' => 'The user can edit GP',
                'group' => 'GP'
            ],
            [
                'name' => 'view',
                'description' => 'The user can view GP',
                'group' => 'GP'
            ],
            [
                'name' => 'create customization',
                'description' => 'The user can create customization',
                'group' => 'settings'
            ],
            [
                'name' => 'edit customization',
                'description' => 'The user can edit customization',
                'group' => 'settings'
            ],
            [
                'name' => 'view customization',
                'description' => 'The user can view customization',
                'group' => 'settings'
            ],
            [
                'name' => 'delete customization',
                'description' => 'The user can delete customization',
                'group' => 'settings'
            ],
            [
                'name' => 'manage integration',
                'description' => 'The user can integrate with 3rd parties',
                'group' => 'settings'
            ],
        ];

        $roles = [
            [
                'name' => 'super admin',
                'description' => 'Create and edit core functions/features'
            ],
            [
                'name' => 'admin',
                'description' => 'Edit limited functions/features, approve budget'
            ],
            [
                'name' => 'user ',
                'description' => 'Uses the system'
            ],

        ];

        collect($permissions)->each(function ($permission) {
            Permission::create([
                'name' => "{$permission['name']} {$permission['group']}",
                'description' => $permission['description'],
                'group' => $permission['group']
            ]);
        });

        collect($roles)->each(function ($role) use ($permissions) {
            $role = Role::create($role);
            $mappedPermissions = collect($permissions)->map(function ($permission) {
                return [
                    'name' => "{$permission['name']} {$permission['group']}"
                ];
            });

            $role->syncPermissions($mappedPermissions);
        });
    }
}