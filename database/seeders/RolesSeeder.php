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

        $superAdmin = new User;
        $superAdmin->name = 'Super Administrador';
        $superAdmin->email = 's@d.com';
        $superAdmin->password = bcrypt('s@d.com');
        $superAdmin->save();

        $superAdmin->assignRole($superAdminRole);

        $admin = new User;
        $admin->name = 'Administrador';
        $admin->email = 'a@d.com';
        $admin->password = bcrypt('a@d.com');
        $admin->save();

        $admin->assignRole($adminRole);

        $standardOne = new User;
        $standardOne->name = 'Estandard One';
        $standardOne->email = 'o@d.com';
        $standardOne->password = bcrypt('o@d.com');
        $standardOne->save();

        $standardOne->assignRole($standardRole);

        $standardTwo = new User;
        $standardTwo->name = 'Estandard Two';
        $standardTwo->email = 't@d.com';
        $standardTwo->password = bcrypt('t@d.com');
        $standardTwo->save();

        $standardTwo->assignRole($standardRole);

        $basic = new User;
        $basic->name = 'Usuario';
        $basic->email = 'b@d.com';
        $basic->password = bcrypt('b@d.com');
        $basic->save();

        $basic->assignRole($basicRole);
    }
}
