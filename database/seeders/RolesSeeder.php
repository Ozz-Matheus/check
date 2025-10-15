<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $standardRole = Role::create(['name' => 'standard']);
        $basicRole = Role::create(['name' => 'panel_user']);
        $supplierRole = Role::create(['name' => 'supplier']);

        $superAdmin = new User;
        $superAdmin->name = 'Super Administrador';
        $superAdmin->email = 's@h.com';
        $superAdmin->password = bcrypt('s@h.com');
        $superAdmin->save();

        $superAdmin->assignRole($superAdminRole);

        $admin = new User;
        $admin->name = 'Administrador';
        $admin->email = 'a@h.com';
        $admin->password = bcrypt('a@h.com');
        $admin->save();

        $admin->assignRole($adminRole);

        $standardOne = new User;
        $standardOne->name = 'Estandard One';
        $standardOne->email = 'o@h.com';
        $standardOne->password = bcrypt('o@h.com');
        $standardOne->save();

        $standardOne->assignRole($standardRole);

        $standardTwo = new User;
        $standardTwo->name = 'Estandard Two';
        $standardTwo->email = 't@h.com';
        $standardTwo->password = bcrypt('t@h.com');
        $standardTwo->save();

        $standardTwo->assignRole($standardRole);

        $basic = new User;
        $basic->name = 'Usuario';
        $basic->email = 'b@h.com';
        $basic->password = bcrypt('b@h.com');
        $basic->save();

        $basic->assignRole($basicRole);

        $supplierOne = new User;
        $supplierOne->name = 'Provedor 1';
        $supplierOne->email = 'p1@h.com';
        $supplierOne->password = bcrypt('p1@h.com');
        $supplierOne->save();

        $supplierOne->assignRole($supplierRole);

        $supplierTwo = new User;
        $supplierTwo->name = 'Provedor 2';
        $supplierTwo->email = 'p2@h.com';
        $supplierTwo->password = bcrypt('p2@h.com');
        $supplierTwo->save();

        $supplierTwo->assignRole($supplierRole);

    }
}
